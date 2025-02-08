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


namespace app\tenantapi\controller\generate;


use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\generate\ThemeLists;
use app\tenantapi\logic\avatar\TenantAiAvatarRecordLogic;
use app\tenantapi\logic\generate\GenerateThemeLogic;
use app\tenantapi\validate\generate\GenerateThemeValidate;

/**
 * AI文案生成主题控制器
 * Class GenerateController
 * @package app\tenantapi\controller\generate
 */
class ThemeController extends BaseAdminController
{

    public array $notNeedLogin = [''];

    /**
     * @notes 获取列表
     * @return mixed
     * @author yfdong
     * @date 2024/12/23 00:20
     */
    public function lists()
    {
        return $this->dataLists(new ThemeLists());
    }

    /**
     * @notes 添加
     * @return mixed
     * @author yfdong
     * @date 2024/12/23 23:31
     */
    public function add()
    {
        $params = (new GenerateThemeValidate())->post()->goCheck('add');
        if (!GenerateThemeLogic::checkName($params['name'])) {
            return $this->fail('名称重复', [], 1, 1);
        }
        GenerateThemeLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }

    /**
     * @notes 编辑
     * @return mixed
     * @author yfdong
     * @date 2024/12/23 00:20
     */
    public function edit()
    {
        $params = (new GenerateThemeValidate())->post()->goCheck('edit');
        $result = GenerateThemeLogic::edit($params);
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
        $params = (new GenerateThemeValidate())->post()->goCheck('delete');
        GenerateThemeLogic::delete($params);
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
        $params = (new GenerateThemeValidate())->goCheck('detail');
        $result = GenerateThemeLogic::detail($params);
        return $this->data($result);
    }

}