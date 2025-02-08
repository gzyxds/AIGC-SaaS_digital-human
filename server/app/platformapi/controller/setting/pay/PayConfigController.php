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
namespace app\platformapi\controller\setting\pay;

use app\platformapi\controller\BaseAdminController;
use app\platformapi\lists\setting\pay\PayConfigLists;
use app\platformapi\logic\setting\pay\PayConfigLogic;
use app\platformapi\validate\setting\PayConfigValidate;
use think\response\Json;

/**
 * 支付配置
 * Class PayConfigController
 * @package app\platformapi\controller\setting\pay
 */
class PayConfigController extends BaseAdminController
{


    /**
     * @notes 设置支付配置
     * @return Json
     * @author 段誉
     * @date 2023/2/23 16:14
     */
    public function setConfig(): Json
    {
        $params = (new PayConfigValidate())->post()->goCheck();
        PayConfigLogic::setConfig($params);
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取支付配置
     * @return Json
     * @author 段誉
     * @date 2023/2/23 16:14
     */
    public function getConfig(): Json
    {
        $id = (new PayConfigValidate())->goCheck('get');
        $result = PayConfigLogic::getConfig($id);
        return $this->success('获取成功', $result);
    }


    /**
     * @notes
     * @return Json
     * @author 段誉
     * @date 2023/2/23 16:15
     */
    public function lists(): Json
    {
        return $this->dataLists(new PayConfigLists());
    }
}