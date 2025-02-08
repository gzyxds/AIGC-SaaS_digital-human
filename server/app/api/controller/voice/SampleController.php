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


namespace app\api\controller\voice;


use app\api\controller\BaseApiController;
use app\common\service\FileService;
use think\response\Json;


/**
 * 声音合成示例控制器
 * Class TenantVoiceController
 * @package app\tenantapi\controller\voice
 */
class SampleController extends BaseApiController
{

    public array $notNeedLogin = ['presetTimbre'];

    /**
     * @notes 获取系统音色信息
     * @return Json
     * @author yfdong
     * @date 2024/11/30 21:25
     */
    public function presetTimbre(): Json
    {
        $timbre = config('timbre.system_timbre');
        foreach ($timbre as &$item) {
            $uri = "/resource/timbre/".$item['voice_name'].".mp3";
            $item['voiceUrl'] = FileService::getFileUrl($uri);
        }
        return $this->success("获取成功", $timbre);
    }

}