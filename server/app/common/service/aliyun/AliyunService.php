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

namespace app\common\service\aliyun;

//use think\Controller;
use think\facade\Request;

class AliyunService
{
    const TIME_ZONE = "GMT";
    const FORMAT_ISO8601 = "Y-m-d\TH:i:s\Z";
    const URL_ENCODING = "UTF-8";
    const ALGORITHM_NAME = "sha1";
    const ENCODING = "UTF-8";

    /**
     * 获取时间戳
     * 必须符合ISO8601规范，并需要使用UTC时间，时区为+0。
     */
    public static function getISO8601Time($date = null)
    {
        $nowDate = $date ?? new \DateTime('now', new \DateTimeZone('UTC'));
        return $nowDate->format(self::FORMAT_ISO8601);
    }

    /**
     * 获取UUID
     */
    public static function getUniqueNonce()
    {
        return uniqid('', true);
    }

    /**
     * URL编码
     * 使用UTF-8字符集按照RFC3986规则编码请求参数和参数取值。
     */
    public static function percentEncode($value)
    {
        return $value !== null ? str_replace(['+', '*', '%7E'], ['%20', '%2A', '~'], urlencode($value)) : null;
    }

    /***
     * 将参数排序后，进行规范化设置，组合成请求字符串。
     * @param array $queryParamsMap   所有请求参数
     * @return string 规范化的请求字符串
     */
    public static function canonicalizedQuery(array $queryParamsMap)
    {
        ksort($queryParamsMap);
        $queryString = '';
        foreach ($queryParamsMap as $key => $value) {
            $queryString .= '&' . self::percentEncode($key) . '=' . self::percentEncode($value);
        }
        return substr($queryString, 1);
    }

    /***
     * 构造签名字符串
     * @param string $method       HTTP请求的方法
     * @param string $urlPath      HTTP请求的资源路径
     * @param string $queryString  规范化的请求字符串
     * @return string 签名字符串
     */
    public static function createStringToSign($method, $urlPath, $queryString)
    {
        $stringToSign = $method . '&' . self::percentEncode($urlPath) . '&' . self::percentEncode($queryString);
        return $stringToSign;
    }

    /***
     * 计算签名
     * @param string $stringToSign      签名字符串
     * @param string $accessKeySecret   阿里云AccessKey Secret加上与号&
     * @return string 计算得到的签名
     */
    public static function sign($stringToSign, $accessKeySecret)
    {
        $signature = base64_encode(hash_hmac(self::ALGORITHM_NAME, $stringToSign, $accessKeySecret . '&', true));
        return self::percentEncode($signature);
    }

    /***
     * 发送HTTP POST请求
     * @param string $queryString 请求参数
     */
    public static function processPostRequest($queryString)
    {
        $url = "https://nls-slp.cn-shanghai.aliyuncs.com/?" . $queryString;
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));

        $response = curl_exec($ch);
        curl_close($ch);
        if ($response === false) {
            return  curl_error($ch);
        } else {
            return  $response;
        }


    }

    /**
     * 调用CosyVoiceClone接口复刻声音
     * @param string $accessKeyId 阿里云AccessKey ID
     * @param string $accessKeySecret 阿里云AccessKey Secret
     * @param string $voicePrefix 声音前缀
     * @param string $audioFileUrl 音频文件URL
     */
    public static function cosyClone($accessKeyId, $accessKeySecret, $voicePrefix, $audioFileUrl)
    {
        $queryParamsMap = [
            'AccessKeyId' => $accessKeyId,
            'Action' => 'CosyVoiceClone',
            'Version' => '2019-08-19',
            'Timestamp' => self::getISO8601Time(),
            'Format' => 'JSON',
            'RegionId' => 'cn-shanghai',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureVersion' => '1.0',
            'SignatureNonce' => self::getUniqueNonce(),
            'VoicePrefix' => $voicePrefix,
            'Url' => $audioFileUrl
        ];

        $queryString = self::canonicalizedQuery($queryParamsMap);
        $stringToSign = self::createStringToSign('POST', '/', $queryString);
        $signature = self::sign($stringToSign, $accessKeySecret);
        $queryStringWithSign = 'Signature=' . $signature . '&' . $queryString;

        return self::processPostRequest($queryStringWithSign);
    }

    /**
     * 调用ListCosyVoice接口查询某个前缀所有声音的状态。
     * @param string $accessKeyId 阿里云AccessKey ID
     * @param string $accessKeySecret 阿里云AccessKey Secret
     * @param string $voicePrefix 声音前缀
     * @param int $pageIndex 页码
     * @param int $pageSize 页大小
     */
    public static function cosyList($accessKeyId, $accessKeySecret, $voicePrefix, $pageIndex, $pageSize)
    {
        $queryParamsMap = [
            'AccessKeyId' => $accessKeyId,
            'Action' => 'ListCosyVoice',
            'Version' => '2019-08-19',
            'Timestamp' => self::getISO8601Time(),
            'Format' => 'JSON',
            'RegionId' => 'cn-shanghai',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureVersion' => '1.0',
            'SignatureNonce' => self::getUniqueNonce(),
            'VoicePrefix' => $voicePrefix,
            'PageIndex' => $pageIndex,
            'PageSize' => $pageSize
        ];

        $queryString = self::canonicalizedQuery($queryParamsMap);
        $stringToSign = self::createStringToSign('POST', '/', $queryString);
        $signature = self::sign($stringToSign, $accessKeySecret);
        $queryStringWithSign = 'Signature=' . $signature . '&' . $queryString;

        return self::processPostRequest($queryStringWithSign);
    }


}
?>
