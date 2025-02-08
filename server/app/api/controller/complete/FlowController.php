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


namespace app\api\controller\complete;


use app\api\controller\BaseApiController;
use app\api\lists\complete\CompleteFlowLists;
use app\api\logic\complete\FlowLogic;
use app\api\logic\key\KeyPoolLogic;
use app\api\logic\video\TenantVideoLogic;
use app\api\service\AiAvatarService;
use app\api\service\SensitiveService;
use app\common\enum\ModuleEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\file\TenantFile;
use app\common\model\video\TenantVideo;
use app\common\model\voice\TenantVoice;
use app\common\service\FileService;
use app\api\validate\complete\FlowValidate;
use app\tenantapi\validate\user\AdjustUserMoney;
use think\facade\Db;
use think\response\Json;


/**
 * FlowController控制器
 * Class FlowController
 * @package app\tenantapi\controller\complete
 */
class FlowController extends BaseApiController
{

    /**
     * @notes 获取列表
     * @return Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function lists()
    {
        return $this->dataLists(new CompleteFlowLists());
    }


    /**
     * @notes 删除
     * @return Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function delete()
    {
        $params = (new FlowValidate())->post()->goCheck('delete');
        FlowLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function detail()
    {
        $params = (new FlowValidate())->goCheck('detail');
        $result = FlowLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 创建声音克隆合成订单
     * @return Json
     * @author yfdong
     * @date 2024/10/10 20:40
     */
    public function createCompleteFlow()
    {
        try {
            Db::startTrans();
            $params = (new FlowValidate())->post()->goCheck('add');
            if (!isset($params['video_file_id']) && !isset($params['video_id'])) {
                return $this->fail('请选择数字人形象或上传数字人视频文件');
            }
            $params['uid'] = $this->userId;
            $params['host'] = $_SERVER['HTTP_HOST'];
            $params['terminal'] = $this->terminal;
            // 视频时长要求
            if (!isset($params['video_id']) && isset($params['video_file_id'])) {
                $file = TenantFile::query()->where(['id' => $params['video_file_id']])->findOrEmpty()->toArray();
                $url = FileService::getFileUrl($file['uri']);
                $video_duration = (new FileService)->getDurationByUrl($url);
                if ($video_duration < 30 || $video_duration > 60 * 5) {
                    return $this->fail('视频时长请限制于30秒至5分钟之间');
                }
            }
            // 合成文案内容敏感词检验
            $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_SENSITIVE);
            if (!isset($keyPool) || !isset($keyPool['key'])) {
                return $this->fail('请联系管理员配置' . ModuleEnum::MODULE_SENSITIVE_NAME . '密钥');
            }
            try {
                $checkResult = (new SensitiveService)->sensitiveCheck($keyPool['key'], $params['content']);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
            if (!$checkResult['pass']) {
                return $this->fail('合成内容包含敏感词：' . $checkResult['msg']);
            }
            // 算力检测  根据文本内容消耗内容
            $costPower = (new AiAvatarService())->computeViocePowerCost($params['content']);
            if ($costPower == 0) {
                return $this->fail('计算对应音频消耗算力失败');
            }

            // 获取对应音色数据
            if (isset($params['voice_id'])) {
                $timbre = TenantVoice::query()->where(['id' => $params['voice_id']])->findOrEmpty()->toArray();
                if (empty($timbre) || !isset($timbre['id'])) {
                    return $this->fail("所选克隆音色不存在");
                }
            } else {
                $timbre = ['name' => $params['timbre']];
            }

            // 获取对应数字人形象的封面
            if (isset($params['video_id'])) {
                $video = TenantVideo::query()->where(['id' => $params['video_id']])->findOrEmpty()->toArray();
                if (empty($video) || !isset($video['id'])) {
                    return $this->fail("数字人形象不存在");
                }
            } else {
                $video = ['cover' => null];
            }

            // 如果算力不足对用户进行提醒
            $moneyEnough = (new AdjustUserMoney)->checkMoney($costPower, null, ['user_id' => $this->userId, 'action' => AccountLogEnum::DEC]);
            if (!$moneyEnough) {
                return $this->fail("用户余额不足，本次需要算力" . $costPower . "点");
            }
            // 同时创建一个数字人待合成任务
            $avatarRecord = [
                'title'       => $params['video_name'],
                'uid'         => $this->userId,
                'video_id'    => $params['video_id'],
                'mode'        => $params['video_mode'],
                'terminal'    => $this->terminal,
                'cover'       => $video['cover'] ?? null,
                'status'      => 0,
                'timbre_name' => $timbre['name'],
            ];
            FlowLogic::add($params, $avatarRecord);
            Db::commit();
            return $this->success('创建成功', [], 1, 1);
        } catch (\Exception $e) {
            Db::rollback();
            return $this->fail($e->getMessage());
        }
    }
}