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

namespace app\api\logic;

use app\api\logic\power\PowerPackageLogic;
use app\common\enum\PayEnum;
use app\common\logic\BaseLogic;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\ConfigService;
use think\Exception;


/**
 * 充值逻辑层
 * Class RechargeLogic
 * @package app\shopapi\logic
 */
class RechargeLogic extends BaseLogic
{

    /**
     * @notes 充值
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2023/2/24 10:43
     */
    public static function recharge(array $params)
    {
        try {
            // 根据套餐id获取对应的金额和算力值
            $powerPackage = PowerPackageLogic::detail(['id' => $params['package']]);
            if (!isset($powerPackage['id'])) {
                throw new Exception("充值套餐不存在");
            }
            $params['money'] = $powerPackage['cost'];
            $params['power'] = $powerPackage['power'];
            // 套餐是否开启算力赠送
            if (isset($powerPackage['gift']) && $powerPackage['gift'] == 1) {
                $params['power'] += $powerPackage['gift_power'];
            }
            $data = [
                'sn' => generate_sn(RechargeOrder::class, 'sn'),
                'order_terminal' => $params['terminal'],
                'user_id' => $params['user_id'],
                'tenant_id' => $params['tenant_id'],
                'pay_status' => PayEnum::UNPAID,
                'order_amount' => $params['money'],
                'power' => $params['power'],
            ];
            $order = RechargeOrder::create($data);
            return [
                'order_id' => (int)$order['id'],
                'from' => 'recharge'
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 充值配置
     * @param $userId
     * @return array
     * @author 段誉
     * @date 2023/2/24 16:56
     */
    public static function config($userId)
    {
        $userMoney = User::where(['id' => $userId])->value('user_money');
        $minAmount = ConfigService::get('recharge', 'min_amount', 0);
        $status = ConfigService::get('recharge', 'status', 0);

        return [
            'status' => $status,
            'min_amount' => $minAmount,
            'user_money' => $userMoney,
        ];
    }


}