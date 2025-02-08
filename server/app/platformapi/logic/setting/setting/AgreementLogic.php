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

namespace app\platformapi\logic\setting\setting;


use app\common\logic\BaseLogic;
use app\common\model\TenantConfig;

/**
 * 支付配置
 * Class PayConfigLogic
 * @package app\platformapi\logic\setting\pay
 */
class AgreementLogic extends BaseLogic
{

    /**
     * @notes 初始化支付配置是否开启
     * @param mixed $tenant_id
     * @return void
     * @author yfdong
     * @date 2024/09/05 23:01
     */
    public static function initialization(mixed $tenant_id)
    {
        //支付方式配置
        $field = "tenant_id,type,name,value";
        //查询支付方式配置 此处默认为租户号为0的模板数据
        $agreementList = TenantConfig::where(['tenant_id' => 0])->field($field)->select()->toArray();
        foreach ($agreementList as $item) {
            $item['tenant_id'] = $tenant_id;
            TenantConfig::create($item);
        }
    }
}
