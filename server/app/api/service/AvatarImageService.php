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
use app\common\enum\ModuleEnum;
use app\common\enum\YesNoEnum;
use app\common\model\video\TenantVideo;
use app\tenantapi\logic\video\TenantVideoLogic;
use Exception;
use think\facade\Log;

/**
 * 数字人形象service
 */
class AvatarImageService
{
    /*****************************  V3模型创建数字人形象  ****************************/
    public function createAvatarImage($videoId, $title, $videoUrl, $noticeUrl = null, $tenantId = null)
    {
        $keyPool = self::getAvatarConfig($tenantId);
        $params = [
            'name'       => $title,
            'video_url'  => $videoUrl,
            'notify_url' => $noticeUrl ?? self::getNoticeUrl(),
        ];
        $result = (new HttpService)->ckPost(ModuleEnum::AVATAR_TRAIN_API, $keyPool['key'], $params);
        Log::INFO("V3模型创建数字人形象结果" . json_encode($result));
        // 成功保存对应记录
        if ($result['code'] == 200) {
            if (isset($videoId)) {
                TenantVideo::update(['image_id' => $result['data']['videoId']], ['id' => $videoId]);
            }
        } else {
            throw new Exception("创建训练模型失败");
        }
        return $result;
    }


    /**
     * @notes 获取数字人创建形象结果地址
     * @return string
     * @author yfdong
     * @date 2024/12/17 22:39
     */
    public function getNoticeUrl()
    {
        // 获取当前请求的域名 生成结果通知接口地址
        $receiveUrl = "/api/video.avatarVideo/receiveAvatarImage";
        return "https://" . $_SERVER['HTTP_HOST'] . $receiveUrl;
    }


    /**
     * @notes 获取数字人对应渠道是否开通，开通返回对应消耗配置
     * @param $mode
     * @return array
     * @throws Exception
     * @author agan
     * @date 2024/12/17 22:22
     */
    public function getAvatarConfig($tenantId = null)
    {
        $powerConfig = PowerConfigLogic::getAvatarTenantConfig('V1', $tenantId);
        if ($powerConfig['video_mode_status'] != YesNoEnum::YES) {
            $powerConfig = PowerConfigLogic::getAvatarTenantConfig('V3', $tenantId);
            if ($powerConfig['video_mode_status'] != YesNoEnum::YES) {
                throw new Exception("请开启任意一个数字人合成通道");
            }
        }
        // 类型为数字人合成对应的key池配置
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VIDEO, $tenantId);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请联系管理员配置key池中' . ModuleEnum::MODULE_VIDEO_NAME . '密钥！');
        }
        return $keyPool;
    }
}
