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
use app\api\lists\power\PowerPackageLists;
use app\api\logic\power\PowerPackageLogic;
use app\tenantapi\validate\power\TenantPowerPackageValidate;
use think\response\Json;


/**
 * power控制器
 * Class TenantPowerPackageController
 * @package app\tenantapi\controller\power
 */
class PowerPackageController extends BaseApiController
{


    /**
     * @notes 获取power列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function lists()
    {
        return $this->dataLists(new PowerPackageLists());
    }

    /**
     * @notes 获取power详情
     * @return Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function detail(): Json
    {
        $params = (new TenantPowerPackageValidate())->goCheck('detail');
        $result = PowerPackageLogic::detail($params);
        return $this->data($result);
    }


}