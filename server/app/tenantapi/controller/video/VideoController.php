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


namespace app\tenantapi\controller\video;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\video\TenantVideoLists;
use app\tenantapi\logic\video\TenantVideoLogic;
use app\tenantapi\validate\video\TenantVideoValidate;


/**
 * TenantVideo控制器
 * Class TenantVideoController
 * @package app\tenantapi\controller\video
 */
class VideoController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function lists()
    {
        return $this->dataLists(new TenantVideoLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function add()
    {
        $params = (new TenantVideoValidate())->post()->goCheck('add');
        $result = TenantVideoLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TenantVideoLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function edit()
    {
        $params = (new TenantVideoValidate())->post()->goCheck('edit');
        $result = TenantVideoLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantVideoLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function delete()
    {
        $params = (new TenantVideoValidate())->post()->goCheck('delete');
        TenantVideoLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function detail()
    {
        $params = (new TenantVideoValidate())->goCheck('detail');
        $result = TenantVideoLogic::detail($params);
        return $this->data($result);
    }


}