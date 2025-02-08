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
namespace app\platformapi\controller\tenant;

use app\platformapi\controller\BaseAdminController;
use app\tenantapi\lists\user\UserLists;
use app\tenantapi\logic\user\UserLogic;
use app\tenantapi\validate\user\UserValidate;

/**
 * 租户用户控制器
 * Class TenantUserController
 * @package app\platformapi\controller\user
 */
class TenantUserController extends BaseAdminController
{

    /**
     * @notes 获取租户用户列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/09/04 23:36
     */
    public function lists()
    {
        //进行租户信息校验
        (new UserValidate())->goCheck('manager');
        return $this->dataLists(new UserLists());
    }


    /**
     * @notes 获取租户用户详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/09/04 23:36
     */
    public function detail()
    {
        $params = (new UserValidate())->goCheck('detail');
        $detail = UserLogic::detail($params['id']);
        return $this->success('获取租户用户详情成功', $detail);
    }
}