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

declare(strict_types=1);

namespace app\common\service;

use app\common\enum\AdminTerminalEnum;
use app\common\model\Config;
use app\common\model\TenantConfig;

class ConfigService
{

    const platformConfig = ['storage', 'guide'];

    /**
     * @notes 设置配置值
     * @param $type
     * @param $name
     * @param $value
     * @return mixed
     * @author 段誉
     * @date 2021/12/27 15:00
     */
    public static function set(string $type, string $name, $value)
    {
        $original = $value;
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $options = [
            'type'  => $type,
            'name'  => $name,
            'value' => $value,
        ];
        $query = ['type' => $type, 'name' => $name];

        if (!AdminTerminalEnum::isPlatform()) {
            $options['tenant_id'] = request()->tenantId;
            $query['tenant_id'] = request()->tenantId;
        }
        $data = (!AdminTerminalEnum::isPlatform() ? new TenantConfig() : new Config())->where($query)->findOrEmpty();

        if ($data->isEmpty()) {

            (!AdminTerminalEnum::isPlatform() ? new TenantConfig() : new Config())->create($options);
        } else {
            $data->value = $value;
            $data->save();
        }

        // 返回原始值
        return $original;
    }

    /**
     * @notes 获取配置值
     * @param string $type
     * @param string $name
     * @param null $default_value
     * @return array|int|mixed|string
     * @author Tab
     * @date 2021/7/15 15:16
     */
    public static function get(string $type, string $name = '', $default_value = null)
    {
        // 当查询的为文件存储配置时获取平台端配置
        if (in_array($type, self::platformConfig)) {
            $configClass = new Config();
        } else {
            // 根据是否为平台决定查询类
            $configClass = AdminTerminalEnum::isPlatform() ? new Config() : new TenantConfig();
            // 判断是否为租户端
            if (!AdminTerminalEnum::isPlatform()) {
                $query['tenant_id'] = request()->tenantId;
            }
        }

        if (!empty($name)) {
            // 初始化查询条件
            $query = ['type' => $type, 'name' => $name];
            // 查询配置值
            $value = $configClass->where($query)->value('value');
            // 处理 JSON 数据
            if ($value !== null) {
                $json = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $json;
                }
            }
            // 返回配置值
            if ($value !== null) {
                // 处理特殊值
                if ($value === 0 || $value === '0') {
                    return $value;
                }
                return $value;
            }
            // 返回默认值
            if ($default_value !== null) {
                return $default_value;
            }
            // 返回本地配置文件中的值
            return config('project.' . $type . '.' . $name);
        }

        // 取某个类型下的所有name的值
        $data = Config::where(['type' => $type])->column('value', 'name');
        foreach ($data as $k => $v) {
            $json = json_decode($v, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data[$k] = $json;
            }
        }
        if (!empty($data)) {
            return $data;
        } else if ($default_value !== null) {
            return $default_value;
        } else {
            return null;
        }
    }


    /**
     * @notes 获取指定类型所有配置
     * @param string $type
     * @return array
     * @author yfdong
     * @date 2024/11/22 00:07
     */
    public static function getTypeAll(string $type)
    {
        $query['type'] = $type;
        if (in_array($type, self::platformConfig)) {
            $configClass = new Config();
        } else {
            // 根据是否为平台决定查询类
            $configClass = AdminTerminalEnum::isPlatform() ? new Config() : new TenantConfig();
            // 判断是否为租户端
            if (!AdminTerminalEnum::isPlatform()) {
                $query['tenant_id'] = request()->tenantId;
            }
        }
        return $configClass::query()->where($query)->select()->toArray();
    }


    /**
     * @notes 取某个类型下的所有name的值
     * @param string $type
     * @return array|null
     * @author yfdong
     * @date 2024/11/29 00:26
     */
    public static function getType(string $type)
    {
        if ($type === 'storage') {
            $configClass = new Config();
        } else {
            // 根据是否为平台决定查询类
            $configClass = AdminTerminalEnum::isPlatform() ? new Config() : new TenantConfig();
            // 判断是否为租户端
            if (!AdminTerminalEnum::isPlatform()) {
                $query['tenant_id'] = request()->tenantId;
                $configClass = $configClass->where($query);
            }
        }
        // 取某个类型下的所有name的值
        $data = $configClass->where(['type' => $type])->column('value', 'name');
        foreach ($data as $k => $v) {
            $json = json_decode($v, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data[$k] = $json;
            }
        }
        if ($data) {
            return $data;
        }
        return null;
    }

    /**
     * @notes 获取指定租户配置
     * @param string $type
     * @param $tenant_id
     * @param string $name
     * @param $default_value
     * @return int|mixed|string|void
     * @author yfdong
     * @date 2025/01/01 21:51
     */
    public static function getByTenantId(string $type, $tenant_id, string $name, $default_value = null)
    {
        // 获取指定租户对应配置
        if (!empty($name)) {
            // 初始化查询条件
            $query = ['type' => $type, 'name' => $name, 'tenant_id' => $tenant_id];
            // 查询配置值
            $value = TenantConfig::query()->where($query)->value('value');
            // 处理 JSON 数据
            if ($value !== null) {
                $json = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $json;
                }
            }
            // 返回配置值
            if ($value !== null) {
                // 处理特殊值
                if ($value === 0 || $value === '0') {
                    return $value;
                }
                return $value;
            }
            // 返回默认值
            if ($default_value !== null) {
                return $default_value;
            }
            // 返回本地配置文件中的值
            return config('project.' . $type . '.' . $name);
        }

    }

}