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


namespace app\api\service;

use think\Exception;
use think\facade\Log;

class HttpService
{

    /**
     * @notes 封装请求
     * @param $API_URL
     * @param $type
     * @param $get_post_data
     * @param $sign
     * @return bool|string
     * @author yfdong
     * @date 2024/10/10 21:13
     */
    public static function send_curl($API_URL, $type, $get_post_data, $sign)
    {
        $ch = curl_init();
        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $API_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $get_post_data);
        } elseif ($type == 'GET') {
            curl_setopt($ch, CURLOPT_URL, $API_URL . '?' . $get_post_data);
        }
        if ($sign) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['sign:' . $sign]);
        }
        curl_setopt($ch, CURLOPT_REFERER, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


    /**
     * @notes 创客通用封装请求
     * @param $url
     * @param $params
     * @return mixed
     * @throws Exception
     * @author yfdong
     * @date 2024/11/07 22:14
     */
    public function ckPost($url, $key, $params): mixed
    {
        // 允许请求创客失败时 在进行一次请求
        $time = 0;
        $success = false;
        while ($time <= 1 && !$success) {
            $result = self::retryPost($url, $key, $params, $time);
            $success = (
                (isset($result['errcode']) && ($result['errcode'] == 0 || $result['errcode'] == 200))
                ||
                (isset($result['code']) && ($result['code'] == 200 || $result['code'] == 0))
            );
            if (!$success) {
                Log::INFO("创客请求失败,返回信息" . json_encode($result));
            }
            $time++;
        }
        if (!$success) {
            throw new Exception("请求失败" . $result['msg'] ?? '');
        }
        Log::INFO("创客请求成功,返回信息" . json_encode($result));
        return $result;
    }


    public function retryPost($url, $key, $params, $time): mixed
    {
        Log::INFO("创建创客接口:" . $url . "第" . (string)($time + 1) . "次调用参数:" . json_encode($params));
        $params['key'] = $key;
        $params = http_build_query($params);
        $res = HttpService::send_curl($url, 'POST', $params, null);
        if (empty($res)) {
            throw new Exception("创客请求返回为空");
        }
        $result = json_decode($res, true);
        return $result;
    }

}