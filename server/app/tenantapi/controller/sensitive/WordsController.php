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


namespace app\tenantapi\controller\sensitive;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\sensitive\WordsLists;
use app\tenantapi\logic\sensitive\SensitiveSettingLogic;
use app\tenantapi\logic\sensitive\WordsLogic;
use app\tenantapi\validate\sensitive\WordsValidate;


/**
 * 敏感词控制器
 * Class TenantVoiceController
 * @package app\tenantapi\controller\voice
 */
class WordsController extends BaseAdminController
{

    /**
     * @notes 获取敏感词列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/19 23:13
     */
    public function lists(): \think\response\Json
    {
        return $this->dataLists(new WordsLists());
    }


    /**
     * @notes 添加敏感词
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/19 22:55
     */
    public function add(): \think\response\Json
    {
        $params = (new WordsValidate())->post()->goCheck('add');
        $result = WordsLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(SensitiveSettingLogic::getError());
    }


    /**
     * @notes 编辑敏感词
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/19 22:56
     */
    public function edit(): \think\response\Json
    {
        $params = (new WordsValidate())->post()->goCheck('edit');
        $result = WordsLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(SensitiveSettingLogic::getError());
    }


    /**
     * @notes 删除敏感词
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/19 22:56
     */
    public function delete(): \think\response\Json
    {
        $params = (new WordsValidate())->post()->goCheck('delete');
        WordsLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 敏感词详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/19 22:56
     */
    public function detail(): \think\response\Json
    {
        $params = (new WordsValidate())->goCheck('detail');
        $result = WordsLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 获取敏感词相关配置
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/20 00:17
     */
    public function getSensitiveSetting(): \think\response\Json
    {
        $setting = SensitiveSettingLogic::getSensitiveSetting();
        return $this->data($setting);
    }


    /**
     * @notes 设置敏感词相关配置
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/20 00:17
     */
    public function setSensitiveSetting(): \think\response\Json
    {
        $params = (new WordsValidate())->post()->goCheck('setting');
        SensitiveSettingLogic::setSensitiveSetting($params);
        return $this->success('设置成功', [], 1, 1);
    }

}