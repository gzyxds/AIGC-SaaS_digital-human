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

namespace app\tenantapi\controller\setting\power;

use app\common\enum\YesNoEnum;
use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\logic\power\PowerConfigLogic;
use app\tenantapi\validate\setting\PowerSettingValidate;
use think\response\Json;

/**
 * 算力相关设置
 * Class HotSearchController
 * @package app\tenantapi\controller\setting
 */
const ALLOW_CHOOSE = false;
class PowerConfigController extends BaseAdminController
{


    /**
     * @notes 获取音色克隆算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/10/14 21:54
     */
    public function getVoiceCloneConfig(): Json
    {
        $result = PowerConfigLogic::getVoiceCloneConfig();
        return $this->data($result);
    }


    /**
     * @notes 设置音色克隆算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/10/14 21:54
     */
    public function setVoiceCloneConfig(): Json
    {
        $params = (new PowerSettingValidate())->post()->goCheck('clone');
        $result = PowerConfigLogic::setVoiceCloneConfig($params);
        if (false === $result) {
            return $this->fail(PowerConfigLogic::getError() ?: '系统错误');
        }
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取语音合成算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/10/14 21:54
     */
    public function getVoiceConfig(): Json
    {
        $result = PowerConfigLogic::getVoiceConfig();
        return $this->data($result);
    }


    /**
     * @notes 设置语音合成算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/10/14 21:54
     */
    public function setVoiceConfig(): Json
    {
        $params = (new PowerSettingValidate())->post()->goCheck('voice');
        $result = PowerConfigLogic::setVoiceConfig($params);
        if (false === $result) {
            return $this->fail(PowerConfigLogic::getError() ?: '系统错误');
        }
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取数字人全部算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/17 23:56
     */
    public function getAllAvatarConfig(): Json
    {
        $result = PowerConfigLogic::getAllAvatarConfig();
        return $this->data($result);
    }

    /**
     * @notes 获取数字人算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/17 23:56
     */
    public function getAvatarConfig(): Json
    {
        $params = (new PowerSettingValidate())->goCheck('videoGet');
        $result = PowerConfigLogic::getAvatarConfig('V' . $params['mode']);
        // 是否允许选择
        $result['allowChoose'] = ALLOW_CHOOSE;
        return $this->data($result);
    }


    /**
     * @notes 设置数字人算力消耗相关配置
     * @return Json
     * @author yfdong
     * @date 2024/11/17 23:56
     */
    public function setAvatarConfig(): Json
    {
        $params = (new PowerSettingValidate())->post()->goCheck('videoSet');
        if (!ALLOW_CHOOSE && $params['mode'] == 2) {
            return $this->fail('高清模式通道暂未开放');
        }
        // 判断对应模式保存状态是否合规
        if ('1' == $params['mode']) {
            $elseMode = PowerConfigLogic::getAvatarConfig('V3');
        } else {
            $elseMode = PowerConfigLogic::getAvatarConfig('V1');
        }
        if ($params['video_mode_status'] == YesNoEnum::NO && $elseMode['video_mode_status'] == YesNoEnum::NO) {
            return $this->fail('至少开启一个合成通道');
        }
        $result = PowerConfigLogic::setAvatarConfig($params, 'V' . $params['mode']);
        if (false === $result) {
            return $this->fail(PowerConfigLogic::getError() ?: '系统错误');
        }
        return $this->success('设置成功', [], 1, 1);
    }
}