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

namespace app\api\controller\power;

use app\api\controller\BaseApiController;
use app\api\logic\power\PowerConfigLogic;
use app\common\enum\YesNoEnum;
use think\response\Json;

const ALLOW_CHOOSE = false;
/**
 * 算力相关设置
 * Class HotSearchController
 * @package app\tenantapi\controller\setting
 */
class PowerConfigController extends BaseApiController
{

    /**
     * @notes 获取音色克隆消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/18 22:29
     */
    public function getVoiceCloneConfig(): Json
    {
        $result = PowerConfigLogic::getVoiceCloneConfig();
        return $this->data($result);
    }

    /**
     * @notes 获取语音算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/11 23:32
     */
    public function getVoiceConfig(): Json
    {
        $result = PowerConfigLogic::getVoiceConfig();
        return $this->data($result);
    }


    /**
     * @notes 获取视频算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/11 23:32
     */
    public function getAvatarConfig(): Json
    {
        $params = request()->param();
        if (!isset($params['mode']) || empty($params['mode']) || !in_array($params['mode'], ['1', '2', '3'])) {
            return $this->fail('请选择正确数字人视频生成模式');
        }
        $result = PowerConfigLogic::getAvatarConfig('V' . $params['mode']);
        return $this->data($result);
    }


    /**
     * @notes 获取数字人全部算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/25 22:25
     */
    public function getAllAvatarConfig(): Json
    {
        $result = PowerConfigLogic::getAllAvatarConfig();
        // 系统是否允许选择V2接口
        // if (!ALLOW_CHOOSE) {
        //     unset($result['V2']);
        // }
        $returnList = [];
        // 过滤状态为未开启的
        foreach ($result as $key => $value) {
            if ($value['video_mode_status'] == YesNoEnum::YES) {
                $returnList[] = $value;
            }
        }
        return $this->data($returnList);
    }
}