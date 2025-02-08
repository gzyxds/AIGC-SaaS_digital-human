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

namespace app\tenantapi\controller\marketing;

use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\logic\marketing\MarketingConfigLogic;
use app\tenantapi\validate\marketing\MarketingValidate;

/**
 * 营销配置控制器
 */
class MarketingSettingController extends BaseAdminController
{

    /**
     * @notes 获取营销配置
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/16 00:09
     */
    public function getMarketinConfig()
    {
        $data = MarketingConfigLogic::getMarketingConfig();
        return $this->data($data);
    }


    /**
     * @notes 设置营销配置
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/11/16 00:09
     */
    public function setMarketingCinfig()
    {
        $params = (new MarketingValidate())->post()->goCheck('unit');
        MarketingConfigLogic::setMarketingConfig($params);
        return $this->success("设置营销配置成功");
    }


    /**
     * @notes 获取赠送算力配置
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/16 00:22
     */
    public function getGiftConfig()
    {
        $data = MarketingConfigLogic::getGiftConfig();
        return $this->data($data);
    }


    /**
     * @notes 设置注册赠送算力
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/11/16 00:39
     */
    public function setGiftConfig()
    {
        $params = (new MarketingValidate())->post()->goCheck('gift');
        MarketingConfigLogic::setGiftConfig($params);
        return $this->success("设置注册赠送算力成功");
    }


}