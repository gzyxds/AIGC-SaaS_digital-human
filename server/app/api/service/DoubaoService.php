<?php

namespace app\api\service;


use app\api\logic\key\KeyPoolLogic;
use app\common\enum\ModuleEnum;
use Exception;
use think\facade\Log;
use WpOrg\Requests\Requests;

class DoubaoService
{

    /**
     * @notes 解析HTTP数据
     * @param mixed $response
     * @return string
     * @throws Exception
     * @author dyf
     */
    private function parseResponseData(mixed $response): string
    {
        $responseData = json_decode($response, true);
        if (isset($responseData['choices'][0]['message'])) {
            $result = $responseData['choices'][0]['message']['content'];
        } else {
            throw new Exception("豆包请求失败");
        }
        return $result;
    }


    /**
     * AI生成文案
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function createAIContent($params): mixed
    {
        // 获取请求路径
        $api_url = ModuleEnum::CHAT_DOUBAO_URL;

        // 获取密钥Key
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_CHAT);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            throw new Exception('请在后台配置AI文案功能的key');
        }
        //todo 测试删除 ad4904b6-bf8a-40ef-9061-e03a60a37e89
        $api_key = $keyPool['key'];  // 豆包请求 API Key
        // 请求数据
        $data = [
            'model'    => 'ep-20250111142804-7n5d7',
            'messages' => [
                ['role' => 'system', 'content' => '你是豆包，是精通' . $params['theme'] . '领域的人工智能助手.'],
                ['role' => 'user', 'content' => '帮我根据以下描述,不需要描述你的结果，直接返回给我你的答案即可，帮我拓展对应的文案如下：' . $params['content']],
            ],
        ];
        // 将数据编码为 JSON
        $json_data = json_encode($data);
        // 初始化 cURL 会话
        $ch = curl_init($api_url);
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应而不是直接输出
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key, // 添加 Authorization 头
        ]);
        curl_setopt($ch, CURLOPT_POST, true); // 设置为 POST 请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); // 设置 POST 请求的数据

        // 执行 cURL 请求
        $response = curl_exec($ch);
        // 检查是否发生了错误
        if ($response === false) {
            Log::info("AI文案请求失败，返回出错");
            throw new Exception('请求错误: ' . curl_error($ch));
        } else {
            Log::info("豆包请求返回：" . $response);
            $result = self::parseResponseData($response);
        }
        // 关闭 cURL 会话
        curl_close($ch);
        return $result;
    }
}