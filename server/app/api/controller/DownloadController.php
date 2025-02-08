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

namespace app\api\controller;


use app\api\validate\DownloadValidate;
use app\common\cache\ExportCache;
use app\common\service\JsonService;

class DownloadController extends BaseApiController
{

    public array $notNeedLogin = ['download'];

    /**
     * @notes 下载文件 返回二进制文件流
     * @param $url
     * @return bool|string
     * @author yfdong
     * @date 2024/11/27 23:55
     */
    public function download()
    {
        $params = (new DownloadValidate())->goCheck('download');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $params['url']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true); // 获取响应头
        $response = curl_exec($curl);

        if ($response === false) {
            curl_close($curl);
            http_response_code(500);
            echo '下载失败：无法访问目标 URL';
            exit();
        }

        // 获取 HTTP 响应头和文件内容
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $data = substr($response, $headerSize);

        // 提取文件大小
        preg_match('/Content-Length:\s*(\d+)/i', $headers, $matches);
        $contentLength = $matches[1] ?? strlen($data);

        curl_close($curl);

        // 清除缓冲区，避免多余数据干扰
        ob_end_clean();

        // 获取文件扩展名
        $extension = strtolower($params['format']);

        // 根据文件扩展名设置 MIME 类型
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'zip'  => 'application/zip',
            'txt'  => 'text/plain',
            'csv'  => 'text/csv',
            'mp3'  => 'audio/mpeg',
            'mp4'  => 'video/mp4',
            'html' => 'text/html',
            'json' => 'application/json',
        ];
        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

        // 设置下载的文件名
        $fileName = $params['name'] . '.' . $extension;
        $fileNameEncoded = urlencode($fileName);

        // 设置响应头
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $fileNameEncoded . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $contentLength);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');
        header('Accept-Ranges: bytes');

        // 输出文件内容
        echo $data;
        exit();
    }

}