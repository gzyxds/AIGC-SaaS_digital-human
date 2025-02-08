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


namespace app\tenantapi\controller\voicerecord ;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\voicerecord\TenantVoiceRecordLists;
use app\tenantapi\logic\voicerecord\TenantVoiceRecordLogic;
use app\tenantapi\validate\voicerecord\TenantVoiceRecordValidate;


/**
 * TenantVoiceRecord控制器
 * Class TenantVoiceRecordController
 * @package app\tenantapi\controller\voicerecord
 */
class VoiceRecordController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function lists()
    {
        return $this->dataLists(new TenantVoiceRecordLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function add()
    {
        $params = (new TenantVoiceRecordValidate())->post()->goCheck('add');
        $result = TenantVoiceRecordLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TenantVoiceRecordLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function edit()
    {
        $params = (new TenantVoiceRecordValidate())->post()->goCheck('edit');
        $result = TenantVoiceRecordLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantVoiceRecordLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function delete()
    {
        $params = (new TenantVoiceRecordValidate())->post()->goCheck('delete');
        TenantVoiceRecordLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function detail()
    {
        $params = (new TenantVoiceRecordValidate())->goCheck('detail');
        $result = TenantVoiceRecordLogic::detail($params);
        return $this->data($result);
    }


}