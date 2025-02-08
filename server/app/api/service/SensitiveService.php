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

use app\api\logic\key\KeyPoolLogic;
use app\api\logic\sensitive\SensitiveSettingLogic;
use app\api\logic\sensitive\WordsLogic;
use app\common\enum\ModuleEnum;

/**
 * 敏感词检测
 * Class TenantTokenService
 * @package app\api\service
 */
class SensitiveService
{

    /**
     * @notes 敏感词检测
     * @param $key
     * @param $content
     * @return array
     * @author yfdong
     * @date 2024/11/22 00:02
     */
    public function sensitiveCheck($key, $content)
    {
        $result = [
            'pass' => true,
            'word' => null,
            'msg' => null
        ];
        // 获取当前敏感词配置
        $setting = SensitiveSettingLogic::getSensitiveSetting();
        // 在线敏感词检测
        self::onlineSensitiveCheck($key, $content, $result);
        // 租户配置敏感词检测
        if ($setting['sensitive_switch'] == 1 && $result['pass']) {
            $words = (new WordsLogic)->usedWords();
            // 提取并合并所有words，使用逗号分隔
            $wordsArray = [];
            foreach ($words as $item) {
                // 将每个 "words" 字符串分割为数组并合并
                $words = explode('、', $item['words']);
                $wordsArray = array_merge($wordsArray, $words);
            }
            //  去重
            $wordsArray = array_unique($wordsArray);
            //  检测一个字符串是否包含这些字符
            foreach ($wordsArray as $word) {
                if (strpos($content, $word) !== false) {
                    // 如果包含某个单词，返回 true
                    $result['pass'] = false;
                    $result['word'][] = $word;
                }
            }
        }
        if (!$result['pass']) {
            $result['msg'] = implode(",", $result['word']);
        }
        return $result;
    }


    /**
     * @notes 在线敏感词检测
     * @param $key
     * @param $content
     * @param $checkResult
     * @return void
     * @throws \think\Exception
     * @author yfdong
     * @date 2024/11/22 00:03
     */
    public function onlineSensitiveCheck($key, $content, &$checkResult)
    {
        // 在线敏感词检测
        $params = [
            'content' => $content
        ];
        $result = (new HttpService)->ckPost(ModuleEnum::SENSITIVE_API_URL, $key, $params);
        if (sizeof($result['data']) > 0) {
            $checkResult['pass'] = false;
            foreach ($result['data'] as $item) {
                $checkResult['word'][] = $item['word'];
            }
        }
    }

}