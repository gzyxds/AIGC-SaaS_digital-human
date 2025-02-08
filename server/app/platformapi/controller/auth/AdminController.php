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

namespace app\platformapi\controller\auth;

use app\platformapi\controller\BaseAdminController;
use app\platformapi\lists\auth\AdminLists;
use app\platformapi\validate\auth\AdminValidate;
use app\platformapi\logic\auth\AdminLogic;
use app\platformapi\validate\auth\editSelfValidate;

/**
 * 管理员控制器
 * Class AdminController
 * @package app\platformapi\controller\auth
 */
class AdminController extends BaseAdminController
{

    /**
     * @notes 查看管理员列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 9:55
     */
    public function lists()
    {
        return $this->dataLists(new AdminLists());
    }


    /**
     * @notes 添加管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 10:21
     */
    public function add()
    {
        $params = (new AdminValidate())->post()->goCheck('add');
        $result = AdminLogic::add($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }


    /**
     * @notes 编辑管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:03
     */
    public function edit()
    {
        $params = (new AdminValidate())->post()->goCheck('edit');
        $result = AdminLogic::edit($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }


    /**
     * @notes 删除管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:03
     */
    public function delete()
    {
        $params = (new AdminValidate())->post()->goCheck('delete');
        $result = AdminLogic::delete($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }


    /**
     * @notes 查看管理员详情
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public function detail()
    {
        $params = (new AdminValidate())->goCheck('detail');
        $result = AdminLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 获取当前管理员信息
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/31 10:53
     */
    public function mySelf()
    {
        $result = AdminLogic::detail(['id' => $this->adminId], 'auth');
        return $this->data($result);
    }


    /**
     * @notes 编辑超级管理员信息
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/4/8 17:54
     */
    public function editSelf()
    {
        $params = (new editSelfValidate())->post()->goCheck('', ['admin_id' => $this->adminId]);
        $result = AdminLogic::editSelf($params);
        return $this->success('操作成功', [], 1, 1);
    }

}