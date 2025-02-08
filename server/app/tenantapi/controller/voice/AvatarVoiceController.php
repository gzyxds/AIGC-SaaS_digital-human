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


namespace app\tenantapi\controller\voice;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\voice\TenantVoiceLists;
use app\tenantapi\logic\voice\TenantVoiceLogic;
use app\tenantapi\validate\voice\TenantVoiceValidate;


/**
 * TenantVoice控制器
 * Class TenantVoiceController
 * @package app\tenantapi\controller\voice
 */
class AvatarVoiceController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function lists()
    {
        return $this->dataLists(new TenantVoiceLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function add()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('add');
        $result = TenantVoiceLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TenantVoiceLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function edit()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('edit');
        $result = TenantVoiceLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantVoiceLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function delete()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('delete');
        TenantVoiceLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function detail()
    {
        $params = (new TenantVoiceValidate())->goCheck('detail');
        $result = TenantVoiceLogic::detail($params);
        return $this->data($result);
    }


}