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
use app\common\enum\ModuleEnum;
use app\common\model\voice\TenantVoice;
use think\Exception;
use think\facade\Log;

/**
 *  克隆声音服务
 */
class CloneVoiceService
{
    /*****************************  声音转文字 | 字幕 | 字幕对轴｜声音校对接口开始  ****************************/

    /**
     * @notes
     * @param int $mode
     * @param string $audioUrl
     * @param string $receiveUrl
     * @param string|null $sourceText
     * @return mixed
     * @throws Exception
     * @author yfdong
     * @date 2024/12/09 22:28
     */
    public function mediaToText(int $mode, string $audioUrl, string $receiveUrl, string $sourceText = null): mixed
    {
        // 声音校对使用key池中声音克隆key
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_CLONE);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请先配置key池中' . ModuleEnum::MODULE_CLONE_NAME . '密钥！');
        }
        $params = [
            'stems'       => 1,
            'lang'        => 'zh',
            'mode'        => $mode,
            'audio_url'   => $audioUrl,
            'notify_url'  => $receiveUrl,
            'source_text' => $sourceText,
        ];
        return (new HttpService)->ckPost(ModuleEnum::MEDIA_TO_TEXT, $keyPool['key'], $params);
    }

    /***************************** 声音转文字 | 字幕 | 字幕对轴｜声音校对接口开始  ***************************/

    /***************************************  V2模型声音合成开始  ****************************/

    /**
     * @notes 创建V2模型声音合成
     * @param $voiceInfo
     * @param $content
     * @return null
     * @throws Exception
     * @author yfdong
     * @date 2024/11/07 22:43
     */
    public function createCloneVoiceV2($voiceInfo, $receiveUrl, $params, $tenantId = null): array
    {
        // 获取音色名称
        if (isset($voiceInfo['timbre_name']) && !empty($voiceInfo['timbre_name'])) {
            $timbre = $voiceInfo['timbre_name'];
        } else {
            $timbre = self::createTimbre($voiceInfo['id'], $voiceInfo['url'], $tenantId);
        }
        // 创建声音模型合成音频
        return self::cloneVoiceV2($timbre, $params, $receiveUrl, $tenantId);
    }


    /**
     * @notes V2声音模型创建音色
     * @param $voiceUrl
     * @return void
     * @throws Exception
     * @author yfdong
     * @date 2024/11/07 22:09
     */
    public function createTimbre($voiceId, $voiceUrl, $tenantId): string
    {
        // 获取key池中V2声音克隆key
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_CLONE, $tenantId);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请先配置key池中' . ModuleEnum::MODULE_CLONE_NAME . '密钥！');
        }
        // 组装请求参数
        $params['audio_url'] = $voiceUrl;
        $result = (new HttpService)->ckPost(ModuleEnum::CLONE_API_URL_V2, $keyPool['key'], $params);
        $timbre = $result['data'];
        // 保存对应音色
        TenantVoice::update(['timbre_name' => $timbre], ['id' => $voiceId]);
        return $timbre;
    }

    /**
     * @notes V2声音模型合成音频
     * @param $voiceUrl
     * @return void
     * @throws Exception
     * @author yfdong
     * @date 2024/11/07 22:09
     */
    public function cloneVoiceV2($timbre, $data, $receiveUrl, $tenantId = null): array
    {
        // 将 speed 转换为浮点数
        $data['speed'] = (float)$data['speed'];

        // 判断并进行范围映射
        if ($data['speed'] >= 0.1 && $data['speed'] <= 2) {
            // 将 speed 映射到 -500 到 500 的范围
            $data['speech_rate'] = ($data['speed'] - 0.1) * (1000 / 1.9) - 500;
        } else {
            // 如果不在 0.1 到 2 的范围内，则保持原样或做其他处理
            // 例如，你可以返回错误，或让它保持默认值
            $data['speech_rate'] = 0; // 默认处理
        }

        // 组装请求参数
        $params = [
            'voicename'   => $timbre,
            'text'        => str_replace(["\r", "\n"], '。', $data['content']),
            'speech_rate' => floor($data['speech_rate']),
            'type'        => 1,
            'notify_url'  => $receiveUrl,
        ];
        // 获取key池中V2声音合成key
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VOICE, $tenantId);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请先配置key池中' . ModuleEnum::MODULE_VOICE_NAME . '密钥！');
        }
        $result = (new HttpService)->ckPost(ModuleEnum::VOICE_API_URL_V2, $keyPool['key'], $params);
        return $result;
    }

    /***************************************  V2模型声音合成结束  ****************************/


    /**
     * @notes V1通道合成
     * @param $voiceInfo
     * @param $receiveUrl
     * @param $params
     * @param $voiceUrl
     * @param $tenantId
     * @return array
     * @throws Exception
     * @author yfdong
     * @date 2025/01/01 22:37
     */
    public function createCloneVoiceV1($voiceInfo, $receiveUrl, $params, $voiceUrl, $tenantId = null): array
    {
        // 获取key池中V1声音合成key
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VOICE, $tenantId);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请联系管理员配置' . ModuleEnum::MODULE_VOICE_NAME . '密钥');
        }
        $params['content'] = preg_replace_callback(
            "/([^\r\n]*)([\r\n]+)/",  // 匹配非换行符的内容和换行符
            function ($matches) {
                // $matches[1] 是非换行符内容部分，$matches[2] 是换行符部分
                $content = $matches[1];
                $lineBreak = $matches[2];
                // 检查内容末尾是否已包含标点符号
                if (!preg_match('/[。！？,.]$/u', $content)) {
                    // 如果末尾没有标点符号，添加句号
                    $content .= '。';
                }
                return $content . $lineBreak;
            },
            $params['content']
        );
        // 调用接口获取任务号
        $params_v1 = array(
            'source_audio_url' => $voiceUrl,
            'target_text'      => $params['content'],
            'notify_url'       => $receiveUrl,
            'speed'            => $params['speed'],
            'ref_text'         => $voiceInfo['actual_content'],
        );
        return (new HttpService)->ckPost(ModuleEnum::VOICE_API_URL, $keyPool['key'], $params_v1);
    }

}