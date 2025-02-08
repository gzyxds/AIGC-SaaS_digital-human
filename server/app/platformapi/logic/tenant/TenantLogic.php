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
namespace app\platformapi\logic\tenant;

use app\common\enum\user\UserTerminalEnum;
use app\common\logic\BaseLogic;
use app\common\model\tenant\Tenant;
use app\common\model\user\User;
use Exception;

/**
 * 用户逻辑层
 * Class TenantLogic
 * @package app\platformapi\logic\user
 */
class TenantLogic extends BaseLogic
{
    /**
     * @notes 新增租户
     * @param array $params
     * @return Tenant|\think\Model
     * @throws Exception
     * @author JXDN
     * @date 2024/09/03 14:42
     */
    public static function add(array $params)
    {
        try {
            $domain_alias = preg_replace('/^https?:\/\/|\/$/', '', $params['domain_alias']);
            $sn = $params['host_name'] ?: Tenant::createUserSn();
            $exists = (new Tenant())->where('sn', $sn)->find();
            if(!empty($exists)) {
                throw new Exception('主机名已被占用，请更换');
            }
            return Tenant::create([
                'sn'                  => $sn,
                'name'                => $params['name'],
                'avatar'              => $params['avatar'],
                'tel'                 => $params['tel'],
                'domain_alias'        => $domain_alias,
                'domain_alias_enable' => $params['domain_alias_enable'],
                'disable'             => $params['disable'] ?? 0,
                'notes'               => $params['notes'] ?? '',
                'tactics'             => $params['tactics'] ?? 0,
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 用户详情
     * @param int $userId
     * @return array|false
     * @author JXDN
     * @date 2024/09/11 15:48
     */
    public static function detail(int $userId)
    {
        try {
            $field = "id,sn,name,avatar,tel,domain_alias,domain_alias_enable,disable,create_time,notes";

            $user = Tenant::where(['id' => $userId])->field($field)->findOrEmpty();
            $user['user_total'] = User::where(['tenant_id' => $userId])->count();

            $domain = self::getRootDmain(request()->domain());
            $user['default_domain'] = (self::checkHttp() ? 'https://' : 'http://') . $user['sn'] . '.' . $domain . '/admin/';
            if ($user['domain_alias_enable'] === 0) {
                $user['domain'] = (self::checkHttp() ? 'https://' : 'http://') . $user['domain_alias'] . '/admin/';
            } else {
                $user['domain'] = $user['default_domain'];
            }

            return $user->toArray();
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }

    }

    /**
     * @notes 更新租户信息
     * @param array $params
     * @return bool
     * @author JXDN
     * @date 2024/09/03 14:28
     */
    public static function edit(array $params)
    {
        try {
            $domain_alias = preg_replace('/^https?:\/\/|\/$/', '', $params['domain_alias']);
            Tenant::update([
                'name'                => $params['name'],
                'avatar'              => $params['avatar'],
                'disable'             => $params['disable'] ?? 0,
                'tel'                 => $params['tel'],
                'domain_alias'        => $domain_alias,
                'domain_alias_enable' => $params['domain_alias_enable'],
                'notes'               => $params['notes'] ?? '',
            ], ['id' => $params['id']]);
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除租户
     * @param array $params
     * @return bool
     * @author JXDN
     * @date 2024/09/03 17:04
     */
    public static function delete(array $params)
    {
        try {
            Tenant::destroy($params['id']);
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 检查是否为https
     * @return bool
     * @author JXDN
     * @date 2024/09/11 14:39
     */
    public static function checkHttp()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @notes 获取根域名
     * @param $url
     * @return array|int|string|null
     * @author JXDN
     * @date 2024/09/11 14:49
     */
    public static function getRootDmain($url)
    {
        // 解析 URL 获取主机名
        $host = parse_url($url, PHP_URL_HOST);

        // 如果主机名为空，返回 null
        if (!$host) {
            return null;
        }

        // 拆分域名
        $parts = explode('.', $host);

        // 检查域名的级数
        $numParts = count($parts);

        // 针对常见的两级或三级域名进行处理
        if ($numParts >= 2) {
            // 获取最后两部分，例如 qq.com 或 co.uk
            return $parts[$numParts - 2] . '.' . $parts[$numParts - 1];
        }

        return $host; // 当域名本身就是根域名时，直接返回
    }
}