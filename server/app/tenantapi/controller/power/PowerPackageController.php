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


namespace app\tenantapi\controller\power;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\power\TenantPowerPackageLists;
use app\tenantapi\logic\power\TenantPowerPackageLogic;
use app\tenantapi\validate\power\TenantPowerPackageValidate;


/**
 * power控制器
 * Class TenantPowerPackageController
 * @package app\tenantapi\controller\power
 */
class PowerPackageController extends BaseAdminController
{


    /**
     * @notes 获取power列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function lists()
    {
        return $this->dataLists(new TenantPowerPackageLists());
    }


    /**
     * @notes 添加power
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function add()
    {
        $params = (new TenantPowerPackageValidate())->post()->goCheck('add');
        $result = TenantPowerPackageLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TenantPowerPackageLogic::getError());
    }


    /**
     * @notes 编辑power
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function edit()
    {
        $params = (new TenantPowerPackageValidate())->post()->goCheck('edit');
        $result = TenantPowerPackageLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantPowerPackageLogic::getError());
    }


    /**
     * @notes 删除power
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function delete()
    {
        $params = (new TenantPowerPackageValidate())->post()->goCheck('delete');
        TenantPowerPackageLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取power详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function detail()
    {
        $params = (new TenantPowerPackageValidate())->goCheck('detail');
        $result = TenantPowerPackageLogic::detail($params);
        return $this->data($result);
    }


}