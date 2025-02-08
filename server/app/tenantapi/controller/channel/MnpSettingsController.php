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

namespace app\tenantapi\controller\channel;

use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\logic\channel\MnpSettingsLogic;
use app\tenantapi\validate\channel\MnpSettingsValidate;
use think\response\Json;

/**
 * 小程序设置
 * Class MnpSettingsController
 * @package app\tenantapi\controller\channel
 */
class MnpSettingsController extends BaseAdminController
{
    /**
     * @notes 获取小程序配置
     * @return \think\response\Json
     * @author ljj
     * @date 2022/2/16 9:38 上午
     */
    public function getConfig()
    {
        $result = (new MnpSettingsLogic())->getConfig();
        return $this->data($result);
    }

    /**
     * @notes 设置小程序配置
     * @return \think\response\Json
     * @author ljj
     * @date 2022/2/16 9:51 上午
     */
    public function setConfig()
    {
        $params = (new MnpSettingsValidate())->post()->goCheck();
        (new MnpSettingsLogic())->setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 上传小程序
     * @return Json
     * @author mjf
     * @date 2025/1/8 17:28
     */
    public function uploadMnp(): Json
    {
        $params = $this->request->post();
        $result = (new MnpSettingsLogic())->uploadMnp($params);
        if(false === $result) {
            return $this->fail(MnpSettingsLogic::getError());
        }
        return $this->success('', $result);
    }
}