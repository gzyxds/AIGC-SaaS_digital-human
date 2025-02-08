<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------


namespace app\api\service;

use app\api\logic\key\KeyPoolLogic;
use app\api\logic\power\PowerConfigLogic;
use app\api\logic\video\TenantVideoLogic;
use app\api\logic\voicerecord\TenantVoiceRecordLogic;
use app\common\enum\ModuleEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\enum\YesNoEnum;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\tenantapi\logic\user\UserLogic;
use app\tenantapi\validate\user\AdjustUserMoney;
use Exception;
use think\facade\Log;
use think\response\Json;

/**
 * 管理员token
 * Class TenantTokenService
 * @package app\tenantapi\service
 */
class AiAvatarService
{
    /**
     * @notes 计算生成数字人消耗算力
     * @param $url
     * @return int
     * @author yfdong
     * @date 2024/10/20 22:56
     */
    public function computePowerCost($duration, $mode, $tenantId = null)
    {
        // 获取对应租户的配置
        $powerConfig = PowerConfigLogic::getAvatarTenantConfig($mode, $tenantId);
        // 获取音频时长
        $minutes = ceil($duration / 60);
        // 计算算力消耗
        return $powerConfig['video_power'] * ceil($minutes / $powerConfig['video_time']);
    }

    /**
     * @notes 计算生成克隆声消耗算力
     * @param $text
     * @return float|int
     * @author yfdong
     * @date 2024/10/29 20:48
     */
    public function computeViocePowerCost($text, $tenantId = null)
    {
        if(null == $tenantId){
            // 获取对应租户的配置
            $powerConfig = PowerConfigLogic::getVoiceConfig();
        }else{
            // 获取对应租户的配置
            $powerConfig = PowerConfigLogic::getVoiceConfigByTenant($tenantId);
        }
        // 计算算力消耗
        return $powerConfig['voice_power'] * ceil(mb_strlen($text, 'UTF-8') / $powerConfig['voice_words']);
    }


    /**
     * @notes 创建数字人视频
     * @param $voiceId
     * @param $videoId
     * @param $mode
     * @param $userId
     * @param $noticeUrl
     * @param $tenantId
     * @param $voiceUrl
     * @param $videoUrl
     * @return mixed
     * @throws \think\Exception
     * @throws Exception
     * @author yfdong
     * @date 2025/01/06 22:35
     */
    public function createAiAvatar($voiceId, $videoId, $mode, $userId, $noticeUrl, $tenantId = null, $voiceUrl = null, $videoUrl = null): mixed
    {
        // 获取对应视频音频文件地址
        if (!isset($voiceId) && !empty($voiceUrl)) {
            $voiceUrl = (new TenantVoiceRecordLogic)->queryUrlById($voiceId, $userId);
        }
        if (!isset($videoUrl) && !empty($videoId)) {
            $videoUrl = (new TenantVideoLogic)->queryUrlById($videoId, $userId);
        }
        // 获取通知接口路径
        $noticeUrl = $noticeUrl ?? self::getNoticeUrl();
        // 获取对应数字人生成key池配置
        $mode = "V" . $mode;
        $keyPool = self::getAvatarConfig($mode, $tenantId);
        // 进行创客数字人接口请求
        $urls = [
            'V1' => ModuleEnum::AVATAR_API_URL,
            'V2' => ModuleEnum::AVATAR_API_URL_V2,
        ];
        $url = $urls[$mode] ?? null;
        if ($url === null) {
            throw new Exception("接口模式对应接口请求路径不存在");
        }
        // 调用接口获取任务号
        return (new HttpService)->ckPost($url, $keyPool['key'], array(
            'audio_url'  => $voiceUrl,
            'video_url'  => $videoUrl,
            'notify_url' => $noticeUrl,
        ));
    }


    /**
     * @notes 获取数字人通知地址
     * @return string
     * @author yfdong
     * @date 2024/12/15 23:45
     */
    public function getNoticeUrl()
    {
        // 获取当前请求的域名 生成结果通知接口地址
        $receiveUrl = "/api/avatar.aiAvatarRecord/receiveAiAvatar";
        return "https://" . $_SERVER['HTTP_HOST'] . $receiveUrl;
    }


    /**
     * @notes 获取数字人对应渠道是否开通，开通返回对应消耗配置
     * @param $mode
     * @return array
     * @throws Exception
     * @author yfdong
     * @date 2024/12/16 23:22
     */
    public function getAvatarConfig($mode, $tenantId = null)
    {
        if (null == $tenantId) {
            $powerConfig = PowerConfigLogic::getAvatarConfig($mode);
        } else {
            $powerConfig = PowerConfigLogic::getAvatarTenantConfig($mode, $tenantId);
        }
        if ($powerConfig['video_mode_status'] != YesNoEnum::YES) {
            throw new Exception($mode . "通道未开启");
        }
        // 类型为数字人合成对应的key池配置
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VIDEO, $tenantId);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请联系管理员配置key池中' . ModuleEnum::MODULE_VIDEO_NAME . '密钥！');
        }
        return $keyPool;
    }

    /**
     * @notes 计算消耗算力并保存
     * @author yfdong
     * @date 2024/12/16 23:27
     */
    private static function costPower($duration, $mode, $userId): float|int
    {
        // 计算消耗算力
        $costPower = (new AiAvatarService())->computePowerCost($duration, $mode);
        // 如果算力不足对用户进行提醒
        $moneyEnough = (new AdjustUserMoney)->checkMoney($costPower, null, ['user_id' => $userId, 'action' => AccountLogEnum::DEC]);
        if (!$moneyEnough) {
            throw new Exception("用户余额不足，本次需要算力" . $costPower . "点");
        }
        return $costPower;
    }


    /**
     * @notes 保存用户数字人合成记录信息
     * @param $userId
     * @param $costPower
     * @param $taskId
     * @param $title
     * @param $cover
     * @param $voiceId
     * @param $videoId
     * @return TenantAiAvatarRecord|\think\Model
     * @author yfdong
     * @date 2024/12/16 23:54
     */
    public function saveRecord($userId, $costPower, $taskId, $voiceId, $videoId, $title = null, $cover = null)
    {
        // 保存对应用户算力消耗记录
        $userAccount = [
            'user_id'    => $userId,
            // 类型为减少
            'action'     => AccountLogEnum::DEC,
            'changeType' => AccountLogEnum::UM_DEC_ORDER_AVATAR,
            'num'        => $costPower,
            'remark'     => $taskId,
        ];
        UserLogic::adjustUserMoney($userAccount);

        // 创建对应生成数字人视频记录
        $record = [
            'title'      => $title,
            'uid'        => $userId,
            'cover'      => $cover,
            'task_id'    => $taskId,
            'voice_id'   => $voiceId,
            'video_id'   => $videoId,
            'cost_power' => $costPower,
            'status'     => 0,
        ];
        return TenantAiAvatarRecord::create($record);
    }


    /*****************************  V3模型创建数字人形象  ***************************
     * @throws Exception
     */
    public function createAvatarVideo($imageId, $voiceUrl, $noticeUrl = null)
    {
        $keyPool = self::getAvatarConfig("V3");
        $params = [
            'mode_id'    => $imageId,
            'audio_url'  => $voiceUrl,
            'notify_url' => $noticeUrl ?? self::getNoticeUrl() . "V3",
        ];
        $result = (new HttpService)->ckPost(ModuleEnum::AVATAR_API_URL_V3, $keyPool['key'], $params);
        Log::INFO("V3模型生成视频结果" . json_encode($result));
        if ($result['code'] != 200) {
            throw new Exception("V3模型生成视频结果生成失败");
        }
        return $result;
    }

}