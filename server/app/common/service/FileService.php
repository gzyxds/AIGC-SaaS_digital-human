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

namespace app\common\service;

use app\common\enum\AdminTerminalEnum;
use app\common\model\file\File;
use app\common\model\file\TenantFile;
use think\facade\Cache;

class FileService
{

    /**
     * @notes 补全路径
     * @param string $uri
     * @param string $type
     * @return string
     * @author 段誉
     * @date 2021/12/28 15:19
     * @remark
     * 场景一:补全域名路径,仅传参$uri;
     *      例: FileService::getFileUrl('uploads/img.png');
     *      返回 http://www.likeadmin.localhost/uploads/img.png
     *
     * 场景二:补全获取web根目录路径, 传参$uri 和 $type = public_path;
     *      例: FileService::getFileUrl('uploads/img.png', 'public_path');
     *      返回 /project-services/likeadmin/server/public/uploads/img.png
     *
     * 场景三:获取当前储存方式的域名
     *      例: FileService::getFileUrl();
     *      返回 http://www.likeadmin.localhost/
     */
    public static function getFileUrl(string $uri = '', string $type = ''): string
    {
        if (strstr($uri, 'http://')) return $uri;
        if (strstr($uri, 'https://')) return $uri;

        $default = Cache::get('STORAGE_DEFAULT');
        if (!$default) {
            $default = ConfigService::get('storage', 'default', 'local');
            Cache::set('STORAGE_DEFAULT', $default);
        }

        if ($default === 'local') {
            if ($type == 'public_path') {
                return public_path() . $uri;
            }
            $domain = request()->domain();
        } else {
            $storage = Cache::get('STORAGE_ENGINE');
            if (!$storage) {
                $storage = ConfigService::get('storage', $default);
                Cache::set('STORAGE_ENGINE', $storage);
            }
            $domain = $storage ? $storage['domain'] : '';
        }

        return self::format($domain, $uri);
    }


    /**
     * @notes 自行提供host拼接参数
     * @param string $uri
     * @param string $type
     * @return string
     * @author yfdong
     * @date 2025/01/02 22:29
     */
    public function getFileUrlByHost(string $uri = '', string $host = '',string $type = ''): string
    {
        if (strstr($uri, 'http://')) return $uri;
        if (strstr($uri, 'https://')) return $uri;

        $default = Cache::get('STORAGE_DEFAULT');
        if (!$default) {
            $default = ConfigService::get('storage', 'default', 'local');
            Cache::set('STORAGE_DEFAULT', $default);
        }

        if ($default === 'local') {
            if ($type == 'public_path') {
                return public_path() . $uri;
            }
            $domain = "https://".$host;
        } else {
            $storage = Cache::get('STORAGE_ENGINE');
            if (!$storage) {
                $storage = ConfigService::get('storage', $default);
                Cache::set('STORAGE_ENGINE', $storage);
            }
            $domain = $storage ? $storage['domain'] : '';
        }

        return self::format($domain, $uri);
    }

    /**
     * @notes 转相对路径
     * @param $uri
     * @return mixed
     * @author 张无忌
     * @date 2021/7/28 15:09
     */
    public static function setFileUrl($uri)
    {
        $default = ConfigService::get('storage', 'default', 'local');
        if ($default === 'local') {
            $domain = request()->domain();
            return str_replace($domain . '/', '', $uri);
        } else {
            $storage = ConfigService::get('storage', $default);
            return str_replace($storage['domain'] . '/', '', $uri);
        }
    }


    /**
     * @notes 格式化url
     * @param $domain
     * @param $uri
     * @return string
     * @author 段誉
     * @date 2022/7/11 10:36
     */
    public static function format($domain, $uri)
    {
        // 处理域名
        $domainLen = strlen($domain);
        $domainRight = substr($domain, $domainLen - 1, 1);
        if ('/' == $domainRight) {
            $domain = substr_replace($domain, '', $domainLen - 1, 1);
        }

        // 处理uri
        $uriLeft = substr($uri, 0, 1);
        if ('/' == $uriLeft) {
            $uri = substr_replace($uri, '', 0, 1);
        }

        return trim($domain) . '/' . trim($uri);
    }


    /**
     * @notes 通过文件id获取媒体文件时长
     * @param $file_id
     * @return float
     * @throws \Exception
     * @author JXDN
     * @date 2024/12/21 20:12
     */
    public static function getVideoDurationById($file_id)
    {
        try {
            require_once(__DIR__ . '/../../../extend/getid3/getid3.php');
            $getID3 = new \getID3();

            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->field(['uri'])->find($file_id);

            if ($file) {
                $fileUrl = FileService::getFileUrl($file['uri']);

                // 下载远程文件到本地临时目录
                $localFilePath = sys_get_temp_dir() . '/' . uniqid('file_', true) . '.tmp';

                // 下载文件
                self::downloadFileToLocal($fileUrl, $localFilePath);

                // 使用 getID3 分析本地文件
                $fileInfo = $getID3->analyze($localFilePath);

                // 删除临时文件
                unlink($localFilePath);
            }


            // 检查是否成功解析
            if (isset($fileInfo['playtime_seconds'])) {
                // 返回视频时长，单位为秒
                return round($fileInfo['playtime_seconds'], 2); // 保留两位小数
            } else {
                throw new \Exception('无法解析该文件');
            }
        } catch (\Exception $e) {
            // 捕获错误并输出
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @notes 通过url获取媒体文件时长
     * @param $source_url
     * @return float
     * @throws \Exception
     * @author JXDN
     * @date 2024/12/21 20:13
     */
    public function getDurationByUrl($source_url)
    {
        try {
            require_once(__DIR__ . '/../../../extend/getid3/getid3.php');
            $getID3 = new \getID3();

            // 下载远程文件到本地临时目录
            $localFilePath = sys_get_temp_dir() . '/' . uniqid('file_', true) . '.tmp';

            // 下载文件
            self::downloadFileToLocal($source_url, $localFilePath);

            // 使用 getID3 分析本地文件
            $fileInfo = $getID3->analyze($localFilePath);

            // 删除临时文件
            unlink($localFilePath);


            // 检查是否成功解析
            if (isset($fileInfo['playtime_seconds'])) {
                // 返回视频时长，单位为秒
                return round($fileInfo['playtime_seconds'], 2); // 保留两位小数
            } else {
                throw new \Exception('无法解析该文件');
            }
        } catch (\Exception $e) {
            // 捕获错误并输出
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 下载远程文件到本地
     *
     * @param string $remoteUrl 远程文件地址
     * @param string $localPath 本地保存路径
     * @throws \Exception
     */
    public static function downloadFileToLocal($remoteUrl, $localPath)
    {
        if (empty($localPath)) {
            $localPath = sys_get_temp_dir() . '/' . uniqid('file_', true) . '.tmp';
        }
        $fileContent = file_get_contents($remoteUrl);
        if ($fileContent === false) {
            throw new \Exception('无法下载远程文件: ' . $remoteUrl);
        }

        $result = file_put_contents($localPath, $fileContent);
        if ($result === false) {
            throw new \Exception('无法保存文件到本地: ' . $localPath);
        }
    }
}