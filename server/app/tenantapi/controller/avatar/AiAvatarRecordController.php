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


namespace app\tenantapi\controller\avatar;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\avatar\TenantAiAvatarRecordLists;
use app\tenantapi\logic\avatar\TenantAiAvatarRecordLogic;
use app\tenantapi\validate\avatar\TenantAiAvatarRecordValidate;

/**
 * TenantAiAvatarRecord控制器
 * Class TenantAiAvatarRecordController
 * @package app\tenantapi\controller\avatar
 */
class AiAvatarRecordController extends BaseAdminController
{

    public array $notNeedLogin = ['receiveAiAvatar'];

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function lists()
    {
        return $this->dataLists(new TenantAiAvatarRecordLists());
    }

    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function edit()
    {
        $params = (new TenantAiAvatarRecordValidate())->post()->goCheck('edit');
        $result = TenantAiAvatarRecordLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantAiAvatarRecordLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function delete()
    {
        $params = (new TenantAiAvatarRecordValidate())->post()->goCheck('delete');
        TenantAiAvatarRecordLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function detail()
    {
        $params = (new TenantAiAvatarRecordValidate())->goCheck('detail');
        $result = TenantAiAvatarRecordLogic::detail($params);
        return $this->data($result);
    }

}