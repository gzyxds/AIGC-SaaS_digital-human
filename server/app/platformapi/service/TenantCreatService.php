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


namespace app\platformapi\service;


use Exception;
use PDO;
use PDOException;
use think\facade\Config;

/**
 * 创建租户service
 * Class TenantCreatService
 * @package app\platformapi\service
 */
class TenantCreatService
{

    /**
     * 数据库连接
     */
    private $dbh = null;

    /**
     * @notes 创建租户模式分表
     * @param $tenantSn
     * @return bool
     * @throws \Random\RandomException
     * @author yfdong
     * @date 2024/09/28 10:55
     */
    public function createTenantTable($tenantSn)
    {
        // 创建数据库链接
        $this->dbh = self::connectDB();

        // 获取数据库版本
        $version = self::getMysqlVersion($this->dbh);

        // 获取数据库SQL文件路径
        $dbFile = dirname(__DIR__) . '/db/tenant.sql';

        // 检查文件是否存在，若不存在，直接返回错误
        if (!file_exists($dbFile)) {
            throw new Exception("SQL file not found: $dbFile");
        }

        // 读取并规范化SQL文件内容
        $content = str_replace(";\r\n", ";\n", file_get_contents($dbFile));

        // 对数据库中的脚本进行租户唯一标识替换
        $content = str_replace("{tenantSn}", $tenantSn, $content);

        // 分割SQL语句
        $tables = explode(";\n", $content);

        // 初始化安装时间戳
        $installTime = microtime(true) * 10000;

        // 循环处理每个SQL语句
        foreach ($tables as $table) {
            // 去除前后空白字符
            $table = trim($table);

            // 跳过空的SQL语句
            if (empty($table)) continue;

            // 处理数据库版本兼容性问题（版本<=4.1时移除CHARSET）
            if (strpos($table, 'CREATE') !== false && $version <= 4.1) {
                $table = str_replace('DEFAULT CHARSET=utf8', '', $table);
            }

            // 跳过注释的SQL语句
            if (strpos($table, '--') === 0) continue;

            // 替换表前缀
            $table = str_replace('`la_', env('database.database', 'likeadmin_saas') . '.`la_', $table);
            $table = str_replace('`la_', '`' . env('database.prefix', 'la_'), $table);

            // 如果是创建表的SQL，记录表名和时间戳
//            if (strpos($table, 'CREATE') !== false) {
//                $tableName = explode('`', $table)[1];
//                $installTime += random_int(3000, 7000); // 随机递增安装时间
//                $successTable[] = [$tableName, date('Y-m-d H:i:s', $installTime / 10000)];
//            }

            // 执行SQL语句
            try {
                if (!$this->dbh->query($table)) {
                    throw new Exception("SQL execution failed: $table");
                }
            } catch (Exception $e) {
                // 捕获异常并输出详细错误信息
                echo 'Error executing SQL: ' . htmlspecialchars($table) . "<br>";
                echo 'Error message: ' . $e->getMessage() . "<br>";
                return false;
            }
        }

        return true;
    }

    /**
     * @notes 初始化租户数据
     * @param $tenantSn
     * @return bool
     * @throws \Random\RandomException
     * @author yfdong
     * @date 2024/10/01 15:43
     */
    public function initializationTenantData($tenantId, $tenantSn, $param)
    {
        // 创建数据库链接
        $this->dbh = self::connectDB();

        // 获取数据库版本
        $version = self::getMysqlVersion($this->dbh);

        // 获取数据库SQL文件路径
        $dbFile = dirname(__DIR__) . '/db/tenantData.sql';

        // 检查文件是否存在，若不存在，直接返回错误
        if (!file_exists($dbFile)) {
            throw new Exception("SQL file not found: $dbFile");
        }

        // 读取并规范化SQL文件内容
        $content = str_replace(";\r\n", ";\n", file_get_contents($dbFile));

        // 对数据库中的脚本进行租户唯一标识替换
        $content = str_replace("{tenantSn}", $tenantSn, $content);

        // 对数据库中的脚本进行租户唯一标识替换
        $content = str_replace("{tenantId}", $tenantId, $content);

        // 分割SQL语句
        $tables = explode(";\n", $content);

        // 初始化安装时间戳
        $installTime = microtime(true) * 10000;

        // 创建管理员账号
        $tables[] = $this->initAccount($tenantId, $tenantSn, $param);

        // 循环处理每个SQL语句
        foreach ($tables as $table) {
            // 去除前后空白字符
            $table = trim($table);

            // 跳过空的SQL语句
            if (empty($table)) continue;

            // 处理数据库版本兼容性问题（版本<=4.1时移除CHARSET）
            if (strpos($table, 'CREATE') !== false && $version <= 4.1) {
                $table = str_replace('DEFAULT CHARSET=utf8', '', $table);
            }

            // 跳过注释的SQL语句
            if (strpos($table, '--') === 0) continue;

            // 替换表前缀
            $table = str_replace('`la_', env('database.database', 'likeadmin_saas') . '.`la_', $table);
            $table = str_replace('`la_', '`' . env('database.prefix', 'la_'), $table);

            // 执行SQL语句
            try {
                if (!$this->dbh->query($table)) {
                    throw new Exception("SQL execution failed: $table");
                }
            } catch (Exception $e) {
                // 捕获异常并输出详细错误信息
                echo "初始化租户初始数据失败";
                echo 'Error executing SQL: ' . htmlspecialchars($table) . "<br>";
                echo 'Error message: ' . $e->getMessage() . "<br>";
                return false;
            }
        }

        return true;
    }

    /**
     * @notes 检查数据库版本
     * @return string
     * @author yfdong
     * @date 2024/10/01 13:39
     */
    public function getMysqlVersion($dbh)
    {
        $sql = "SELECT VERSION() AS version";
        $result = $dbh->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }


    /**
     * @notes 创建数据库链接
     * @return PDO|string
     * @author yfdong
     * @date 2024/10/01 13:39
     */
    public function connectDB()
    {
        // 获取数据库链接
        $host = env('database.hostname', '');
        $port = env('database.hostport', '');
        $user = env('database.username', '');
        $password = env('database.password', '');
        $dsn = "mysql:host={$host}; port={$port}";
        try {
            $this->dbh = new PDO($dsn, $user, $password);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->exec("SET NAMES 'utf8mb4'");
            $this->dbh->exec("SET NAMES 'utf8mb4'");
            try {
                $this->dbh->exec("SET GLOBAL sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
            } catch (Exception $e) {

            }
            return $this->dbh;
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
    }


    /**
     * @notes 创建管理员账号
     * @param $post
     * @return string
     * @author yfdong
     * @date 2024/10/01 16:02
     */
    public function initAccount($tenantId, $tenantSn, $param)
    {
        $time = time();
        $salt = Config::get('project.unique_identification');
        $defaultPassword = Config::get('project.default_password');

        global $uniqueSalt;
        $uniqueSalt = $salt;

        // 账号
        if (isset($param['account'])) {
            $account = $param['account'];
        } else {
            $account = 'admin';
        }

        //设置密码
        if (isset($param['password'])) {
            $password = md5($salt . md5($param['password'] . $salt));
        } else {
            $password = $defaultPassword;
        }

        // 超级管理员
        $sql = "INSERT INTO `la_tenant_admin_{$tenantSn}`(`id`, `tenant_id`,`root`, `name`, `avatar`, `account`, `password`, `login_time`, `login_ip`, `multipoint_login`, `disable`, `create_time`, `update_time`, `delete_time`) VALUES (1, {$tenantId},1, '超级管理员', '', '{$account}', '{$password}','{$time}', '', 1, 0, '{$time}', '{$time}', NULL);";
        // 超级管理员关联部门
        $sql .= "INSERT INTO `la_tenant_admin_dept_{$tenantSn}` (`admin_id`, `dept_id`) VALUES (1, 1);";

        return $sql;
    }
}