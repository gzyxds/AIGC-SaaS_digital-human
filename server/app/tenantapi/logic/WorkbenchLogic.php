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

namespace app\tenantapi\logic;


use app\common\logic\BaseLogic;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 工作台
 * Class WorkbenchLogic
 * @package app\tenantapi\logic
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作套
     * @param $adminInfo
     * @return array
     * @author 段誉
     * @date 2021/12/29 15:58
     */
    public static function index()
    {
        return [
            // 版本信息
            'version' => self::versionInfo(),
            // 今日数据
            'today' => self::today(),
            // 常用功能
            'menu' => self::menu(),
            // 作品数量
            'works' => self::works(),
            // 服务支持
            'support' => self::support(),
            // 销售数据
            'sale' => self::sale()
        ];
    }


    /**
     * @notes 常用功能
     * @return array[]
     * @author 段誉
     * @date 2021/12/29 16:40
     */
    public static function menu(): array
    {
        return [
            [
                'name' => '管理员',
                'image' => FileService::getFileUrl(config('project.default_image.menu_admin')),
                'url' => '/permission/admin'
            ],
            [
                'name' => '角色管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_role')),
                'url' => '/permission/role'
            ],
            [
                'name' => '部门管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_dept')),
                'url' => '/organization/department'
            ],
            // [
            //     'name' => '字典管理',
            //     'image' => FileService::getFileUrl(config('project.default_image.menu_dict')),
            //     'url' => '/setting/dev_tools/dict'
            // ],
            // [
            //     'name' => '代码生成器',
            //     'image' => FileService::getFileUrl(config('project.default_image.menu_generator')),
            //     'url' => '/setting/dev_tools/code'
            // ],
            [
                'name' => '素材中心',
                'image' => FileService::getFileUrl(config('project.default_image.menu_file')),
                'url' => '/app/material/index'
            ],
            [
                'name' => '菜单权限',
                'image' => FileService::getFileUrl(config('project.default_image.menu_auth')),
                'url' => '/permission/menu'
            ],
            [
                'name' => '网站信息',
                'image' => FileService::getFileUrl(config('project.default_image.menu_web')),
                'url' => '/setting/website/information'
            ],
        ];
    }


    /**
     * @notes 版本信息
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:08
     */
    public static function versionInfo(): array
    {
        return [
            'version' => config('project.version'),
            'website' => config('project.website.url'),
            'name' => ConfigService::get('tenant', 'name'),
            'based' => 'vue3.x、ElementUI、MySQL',
            'channel' => [
                'website' => 'https://www.likeadmin.cn',
                'gitee' => 'https://gitee.com/likeadmin/likeadmin_php_saas',
            ]
        ];
    }


    /**
     * @notes 今日数据
     * @return int[]
     * @author 段誉
     * @date 2021/12/29 16:15
     */
    public static function today(): array
    {
        $voice = TenantVoiceRecord::query()->where('create_time', '>=', strtotime('today'))->count();
        $avatar = TenantAiAvatarRecord::query()->where('create_time', '>=', strtotime('today'))->count();
        $voiceAll = TenantVoiceRecord::query()->count();
        $avatarAll = TenantAiAvatarRecord::query()->count();
        return [
            'time' => date('Y-m-d H:i:s'),
            // 今日销售额
            'today_sales' => RechargeOrder::query()->where('pay_status','1')->where('create_time', '>=', strtotime('today'))->sum('order_amount'),
            // 总销售额
            'total_sales' => RechargeOrder::query()->where('pay_status','1')->sum('order_amount'),
            // 今日作品量
            'today_works' => $voice + $avatar,
            // 总作品量
            'total_works' => $voiceAll + $avatarAll,
            // 今日新增用户量
            'today_new_user' => User::where('create_time', '>=', strtotime('today'))->count(),
            // 总用户量
            'total_new_user' => User::count(),
            // 订单量 (笔)
            'order_num' => RechargeOrder::query()->where('pay_status','1')->where('create_time', '>=', strtotime('today'))->count(),
            // 总订单量
            'order_sum' => RechargeOrder::query()->where('pay_status','1')->count()
        ];
    }

    /**
     * @notes 销售量
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:57
     */
    public static function sale(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $startTime = strtotime(date('Y-m-d', $where_start) . '00:00:00');
            $endTime = strtotime(date('Y-m-d', $where_start) . '23:59:59');
            $num[] = RechargeOrder::query()->whereBetween('create_time', [$startTime, $endTime])->sum('order_amount');
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '销售量', 'data' => $num]
            ]
        ];
    }


    /**
     * @notes 服务支持
     * @return array[]
     * @author 段誉
     * @date 2022/7/18 11:18
     */
    public static function support()
    {
        return [
            [
                'image' => FileService::getFileUrl(config('project.default_image.qq_group')),
                'title' => '官方公众号',
                'desc' => '关注官方公众号',
            ],
            [
                'image' => FileService::getFileUrl(config('project.default_image.customer_service')),
                'title' => '添加企业客服微信',
                'desc' => '想了解更多请添加客服',
            ]
        ];
    }


    /**
     * @notes 作品数量
     * @return array
     * @author yfdong
     * @date 2024/11/17 22:18
     */
    private static function works()
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $startTime = strtotime(date('Y-m-d', $where_start) . '00:00:00');
            $endTime = strtotime(date('Y-m-d', $where_start) . '23:59:59');
            // 数字人和声音合成数量
            $voice = TenantVoiceRecord::query()->whereBetween('create_time', [$startTime, $endTime])->count();
            $avatar = TenantAiAvatarRecord::query()->whereBetween('create_time', [$startTime, $endTime])->count();
            $num[] = $voice + $avatar;
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '作品数量', 'data' => $num]
            ]
        ];
    }


}