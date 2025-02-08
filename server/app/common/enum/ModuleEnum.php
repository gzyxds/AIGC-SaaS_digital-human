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


namespace app\common\enum;


class ModuleEnum
{
    // 功能模块编码
    const MODULE_CLONE = 1; // 克隆音色

    const MODULE_VOICE = 2; // 声音合成

    const MODULE_VIDEO = 3; // 数字人生成

    const MODULE_SENSITIVE = 4; // 敏感词检测

    const MODULE_CHAT = 5; // AI文案豆包

    // 功能模块名称
    const MODULE_CLONE_NAME = "克隆音色"; // 克隆音色

    const MODULE_VOICE_NAME = "声音合成"; // 声音合成

    const MODULE_VIDEO_NAME = "数字人生成"; // 数字人生成

    const MODULE_SENSITIVE_NAME = "敏感词检测"; // 敏感词检测

    const MODULE_CHAT_NAME = "AI文案生成"; // 敏感词检测


    // 功能模块接口路径
    // 数字人合成接口路径
    const AVATAR_API_URL = 'https://api.hihookeji.com/api/humanmeta/index';

    // 数字人合成V2接口路径
    const AVATAR_API_URL_V2 = 'https://api.hihookeji.com/api/enhancehumanmeta/index';
    // 数字人合成V3接口路径
    const AVATAR_API_URL_V3 = "https://api.hihookeji.com/api/humanmetav3/index";
    // 数字人形象训练接口路径
    const AVATAR_TRAIN_API = "https://api.hihookeji.com/api/humanmetatrainv3/index";
    // 声音合成接口路径
    const VOICE_API_URL = 'https://api.hihookeji.com/api/clonevoice/index';
    // 声音合成V2接口路径
    const VOICE_API_URL_V2 = 'https://api.hihookeji.com/api/texttoclonevoicev2/index';
    // 音色克隆V2接口路径
    const MEDIA_TO_TEXT = 'https://api.hihookeji.com/api/mediatotext/index';
    // 音色克隆V2接口路径
    const CLONE_API_URL_V2 = 'https://api.hihookeji.com/api/clonevoicev2/index';
    // 敏感词接口
    const SENSITIVE_API_URL = 'https://api.hihookeji.com/api/sensitivewords/index';

    // AI文案豆包接口
    const CHAT_DOUBAO_URL = 'https://ark.cn-beijing.volces.com/api/v3/chat/completions';


    /**
     * @notes 获取功能模块类型
     * @param $value
     * @return string|string[]
     * @author yfdong
     * @date 2024/11/19 20:54
     */
    public static function getModuleDesc($value = true): array|string
    {
        $data = [
            self::MODULE_CLONE     => self::MODULE_CLONE_NAME,
            self::MODULE_VOICE     => self::MODULE_VOICE_NAME,
            self::MODULE_VIDEO     => self::MODULE_VIDEO_NAME,
            self::MODULE_SENSITIVE => self::MODULE_SENSITIVE_NAME,
            self::MODULE_CHAT      => self::MODULE_CHAT_NAME,
        ];
        if ($value === true) {
            return $data;
        }
        return $data[$value];
    }
}
