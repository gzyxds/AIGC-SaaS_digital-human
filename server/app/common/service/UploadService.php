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
use app\common\enum\FileEnum;
use app\common\model\file\File;
use app\common\model\file\TenantFile;
use app\common\service\storage\Driver as StorageDriver;
use Exception;
use think\facade\Log;

class UploadService
{

    /**
     * @notes 上传图片
     * @param $cid
     * @param int $user_id
     * @param string $saveDir
     * @return array
     * @throws Exception
     * @author 段誉
     * @date 2021/12/29 16:30
     */
    public static function image($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/images')
    {
        try {
            // 租户端文件配置默认获取平台端
            $config = self::getUploadFileConfig();
            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_image'))) {
                throw new Exception("上传图片不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$StorageDriver->upload($saveDir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
                'cid' => $cid,
                'type' => FileEnum::IMAGE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
                'ip' => $_SERVER["REMOTE_ADDR"] ?? null
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @notes 视频上传
     * @param $cid
     * @param int $user_id
     * @param string $saveDir
     * @return array
     * @throws Exception
     * @author 段誉
     * @date 2021/12/29 16:32
     */
    public static function video($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/video')
    {
        try {
            // 租户端文件配置默认获取平台端
            $config = self::getUploadFileConfig();

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_video'))) {
                throw new Exception("上传视频不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$StorageDriver->upload($saveDir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
                'cid' => $cid,
                'type' => FileEnum::VIDEO_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
                'ip' => $_SERVER["REMOTE_ADDR"] ?? null
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 上传文件
     * @param $cid
     * @param int $sourceId
     * @param int $source
     * @param string $saveDir
     * @return array
     * @throws Exception
     * @author dw
     * @date 2023/06/26
     */
    public static function file($cid, int $sourceId = 0, int $source = FileEnum::SOURCE_ADMIN, string $saveDir = 'uploads/file')
    {
        try {
            // 租户端文件配置默认获取平台端
            $config = self::getUploadFileConfig();

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_file'))) {
                throw new Exception("上传文件不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$StorageDriver->upload($saveDir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
                'cid' => $cid,
                'type' => FileEnum::FILE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
                'ip' => $_SERVER["REMOTE_ADDR"] ?? null
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 上传地址
     * @param $saveDir
     * @return string
     * @author dw
     * @date 2023/06/26
     */
    private static function getUploadUrl($saveDir): string
    {
        return $saveDir . '/' . date('Ymd');
    }


    /**
     * @notes 获取文件上传存储配置
     * @return array
     * @author yfdong
     * @date 2024/09/15 19:37
     */
    private static function getUploadFileConfig()
    {
        // 默认开启的存储配置
        $inUse = ConfigService::get('storage', 'default', 'local');
        $config = [
            'default' => $inUse,
            'engine' => 'local' === $inUse ? ['local' => []] : [$inUse => ConfigService::get('storage', $inUse)],
        ];
        return $config;
    }


    /**
     * @notes 保存视频生成结果
     * @param string $url
     * @return array
     * @throws Exception
     * @author yfdong
     * @date 2024/10/13 21:49
     */
    public static function saveResultVideo(string $url, string $fileName, string $saveDir = 'uploads/video'): array
    {
        // 存储引擎
        // 租户端文件配置默认获取平台端
        $config = self::getUploadFileConfig();

        // 1.存储路径和文件名称
        $saveDir = self::getUploadUrl($saveDir);
        $saveDir = $saveDir . '/';
        $fileName = $fileName . '.mp4';

        // 2. 转储文件
        if ($config['default'] == 'local') {
            // 本地存储
            $filePath = download_file($url, $saveDir, $fileName);
        } else {
            // 第三方存储
            $filePath = $saveDir . $fileName;
            $StorageDriver = new StorageDriver($config);
            if (!$StorageDriver->fetch($url, $filePath)) {
                throw new Exception('文件保存失败:' . $StorageDriver->getError());
            }
        }
        // 3、处理文件名称
        if (strlen($fileName) > 128) {
            $name = substr($fileName, 0, 123);
            $nameEnd = substr($fileName, strlen($fileName) - 5, strlen($fileName));
            $fileName = $name . $nameEnd;
        }

        // 4、写入数据库中
        $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
            'cid' => 0,
            'type' => FileEnum::VIDEO_TYPE,
            'name' => $fileName,
            'uri' => $saveDir . str_replace("\\", "/", $fileName),
            'source' => FileEnum::SOURCE_ADMIN,
            'source_id' => 0,
            'create_time' => time(),
            'ip' => $_SERVER["REMOTE_ADDR"] ?? null
        ]);

        // 5、返回结果
        return [
            'id' => $file['id'],
            'cid' => $file['cid'],
            'type' => $file['type'],
            'name' => $file['name'],
            'uri' => FileService::getFileUrl($file['uri']),
            'url' => $file['uri'],
            'size' => self::getRemoteFileSize($url)
        ];
    }


    /**
     * @notes 文件流形式接受数字人视频
     * @param string $fileParamName
     * @param string $taskId
     * @return array
     * @throws Exception
     * @author yfdong
     * @date 2024/10/16 22:37
     */
    public static function saveResultVideoByFile(string $fileParamName, string $taskId)
    {
        try {
            $cid = 0;
            $sourceId = 0;
            $source = FileEnum::SOURCE_ADMIN;
            $saveDir = 'uploads/video';
            // 租户端文件配置默认获取平台端
            $config = self::getUploadFileConfig();

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile($fileParamName);
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            require_once(__DIR__ . '/../../../extend/getid3/getid3.php');
            $getID3 = new \getID3();
            $analyzeFileInfo = $getID3->analyze($fileInfo['realPath']);
            $duration = round($analyzeFileInfo['playtime_seconds'], 2);

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_video'))) {
                throw new Exception("上传视频不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$StorageDriver->upload($saveDir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
                'cid' => $cid,
                'type' => FileEnum::VIDEO_TYPE,
                'name' => $taskId . '.' . $fileInfo['ext'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
                'ip' => $_SERVER["REMOTE_ADDR"] ?? null
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri'],
                'size' => $fileInfo['size'],
                'duration' => $duration
            ];

        } catch (Exception $e) {
            Log::info('接受创建数字人视频结果文件信息失败：' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @notes 获取文件大小
     * @param $url
     * @return false|mixed
     * @author yfdong
     * @date 2024/10/13 23:44
     */
    static function getRemoteFileSize($url)
    {
        // 初始化 cURL
        $ch = curl_init($url);

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_NOBODY, true); // 不下载主体内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回数据而不是直接输出
        curl_setopt($ch, CURLOPT_HEADER, true); // 包括头信息
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 设置超时（可选）

        // 执行 cURL 请求
        curl_exec($ch);

        // 获取 HTTP 响应状态码
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // 获取文件大小
        $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        // 关闭 cURL
        curl_close($ch);

        // 检查状态码和文件大小
        if ($httpCode == 200) {
            return $fileSize; // 返回文件大小（单位：字节）
        } else {
            return false; // 请求失败
        }
    }

    /**
     * @notes 文件流形式接收克隆声
     * @param $cid
     * @param int $sourceId
     * @param int $source
     * @param string $saveDir
     * @return array
     * @throws Exception
     * @author yfdong
     * @date 2024/10/16 22:37
     */
    public static function saveResultVoiceByFile(string $fileParamName, string $taskId)
    {
        try {
            $cid = 0;
            $sourceId = 0;
            $source = FileEnum::SOURCE_ADMIN;
            $saveDir = 'uploads/voice';
            // 租户端文件配置默认获取平台端
            $config = self::getUploadFileConfig();

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile($fileParamName);
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_file'))) {
                throw new Exception("上传文件不允许上传" . $fileInfo['ext'] . "文件");
            }

            require_once(__DIR__ . '/../../../extend/getid3/getid3.php');
            $getID3 = new \getID3();
            $analyzeFileInfo = $getID3->analyze($fileInfo['realPath']);
            $duration = round($analyzeFileInfo['playtime_seconds'], 2);

            // 上传文件
            $saveDir = self::getUploadUrl($saveDir);
            if (!$StorageDriver->upload($saveDir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $name = substr($fileInfo['name'], 0, 123);
                $nameEnd = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $name . $nameEnd;
            }

            // 4、写入数据库中
            $file = (AdminTerminalEnum::isPlatform() ? new File() : new TenantFile())->create([
                'cid' => $cid,
                'type' => FileEnum::VOICE_TYPE,
                'name' => $taskId . $fileInfo['ext'],
                'uri' => $saveDir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $sourceId,
                'create_time' => time(),
                'ip' => $_SERVER["REMOTE_ADDR"] ?? null
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri'],
                'size' => $fileInfo['size'],
                'duration' => $duration
            ];

        } catch (Exception $e) {
            Log::info('接受创建克隆声结果文件信息失败：' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
