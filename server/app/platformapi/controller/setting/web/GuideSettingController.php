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

namespace app\platformapi\controller\setting\web;

use app\platformapi\controller\BaseAdminController;
use app\platformapi\logic\setting\web\GuideSettingLogic;
use app\platformapi\logic\setting\web\WebSettingLogic;
use app\platformapi\validate\setting\GuideSettingValidate;
use app\platformapi\validate\setting\WebSettingValidate;

/**
 * 充值引导配置
 * Class WebSettingController
 * @package app\platformapi\controller\setting
 */
class GuideSettingController extends BaseAdminController
{
    /**
     * @notes 设置充值引导
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/11 23:49
     */
    public function setGuide()
    {
        $params = (new GuideSettingValidate())->post()->goCheck('set');
        GuideSettingLogic::setGuide($params);
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取充值引导
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/11 23:49
     */
    public function getGuide()
    {
        $result = GuideSettingLogic::getGuide();
        return $this->data($result);
    }


}