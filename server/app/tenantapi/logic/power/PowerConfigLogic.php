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

namespace app\tenantapi\logic\power;

use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\service\ConfigService;


/**
 * 热门搜素逻辑
 * Class HotSearchLogic
 * @package app\tenantapi\logic\setting
 */
class PowerConfigLogic extends BaseLogic
{

    /**
     * @notes 获取音色克隆算力消耗相关配置
     * @return array
     * @author yfdong
     * @date 2024/11/18 21:56
     */
    public static function getVoiceCloneConfig()
    {
        return [
            // 音色克隆消耗数
            'clone_power' => ConfigService::get('power', 'clone_power', '1000'),
            // 音频要求录制文案
            'voice_copy'  => ConfigService::get('power', 'voice_copy', '我的声音将用于平台克隆，并合法使用，为自己的行为负责'),
        ];
    }


    /**
     * @notes 设置音色克隆算力消耗相关配置
     * @param $params
     * @return bool
     * @author yfdong
     * @date 2024/11/18 22:02
     */
    public static function setVoiceCloneConfig($params)
    {
        try {
            // 音色克隆消耗数
            ConfigService::set('power', 'clone_power', $params['clone_power']);
            // ConfigService::set('power', 'voice_copy', $params['voice_copy']);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 获取算力消耗配置
     * @return array
     * @author yfdong
     * @date 2024/10/20 22:26
     */
    public static function getVoiceConfig(): array
    {
        return [
            // 是否开启文件上传
            'upload_file' => ConfigService::get('power', 'upload_file', 0),
            // 音频算力消耗数
            'voice_power' => ConfigService::get('power', 'voice_power', '100'),
            // 音频算力消耗数对应字数
            'voice_words' => ConfigService::get('power', 'voice_words', '100'),
        ];
    }


    /**
     * @notes 获取算力消耗配置
     * @param $params
     * @return bool
     * @author yfdong
     * @date 2024/10/20 22:26
     */
    public static function setVoiceConfig($params): bool
    {
        try {
            ConfigService::set('power', 'upload_file', $params['upload_file']);
            ConfigService::set('power', 'voice_power', $params['voice_power']);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 获取数字人全部算力消耗相关配置
     * @return array[]
     * @author yfdong
     * @date 2024/11/25 22:13
     */
    public static function getAllAvatarConfig()
    {
        return [
            'V1' => [
                'mode'              => 1,
                // 视频合成模式 默认标清模式
                'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V1', "极速模式"),
                // 视频合成模式开启状态 默认标清模式
                'video_mode_status' => ConfigService::get('power', 'video_mode_status_V1', YesNoEnum::YES),
                // 视频算力消耗数
                'video_power'       => ConfigService::get('power', 'video_power_V1', 1000),
                // 视频算力消耗数对应时长
                'video_time'        => ConfigService::get('power', 'video_time_V1', 1),
                // 是否禁用
                'disabled'          => 0
            ],
            // 'V2' => [
            //     'mode'              => 2,
            //     // 视频合成模式 默认标清模式
            //     'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V2', "创客API-高清模式"),
            //     // 视频合成模式开启状态 默认标清模式
            //     'video_mode_status' => ConfigService::get('power', 'video_mode_status_V2', 0),
            //     // 视频算力消耗数
            //     'video_power'       => ConfigService::get('power', 'video_power_V2', 2000),
            //     // 视频算力消耗数对应时长
            //     'video_time'        => ConfigService::get('power', 'video_time_V2', 1),
            //     // 是否禁用
            //     'disabled'          => 1
            // ],
            'V3' => [
                'mode'              => 3,
                // 视频合成模式 默认标清模式
                'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V3', "专业模式"),
                // 视频合成模式开启状态 默认标清模式
                'video_mode_status' => ConfigService::get('power', 'video_mode_status_V3', YesNoEnum::NO),
                // 视频算力消耗数
                'video_power'       => ConfigService::get('power', 'video_power_V3', 4000),
                // 视频算力消耗数对应时长
                'video_time'        => ConfigService::get('power', 'video_time_V3', 1),
                // 是否禁用
                'disabled'          => 0
            ],
        ];
    }

    /**
     * @notes 获取数字人合成算力消耗配置
     * @return array
     * @author yfdong
     * @date 2024/11/17 23:55
     */
    public static function getAvatarConfig($mode)
    {
        $modes = [
            'V1' => [
                // 视频合成模式 默认标清模式
                'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V1', "极速模式"),
                // 视频合成模式开启状态 默认标清模式开启
                'video_mode_status' => ConfigService::get('power', 'video_mode_status_V1', YesNoEnum::YES),
                // 视频算力消耗数
                'video_power'       => ConfigService::get('power', 'video_power_V1', 1000),
                // 视频算力消耗数对应时长 分钟
                'video_time'        => ConfigService::get('power', 'video_time_V1', 1),
            ],
            // 'V2' => [
            //     // 视频合成模式 默认标清模式
            //     'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V2', "创客API-高清模式"),
            //     // 视频合成模式开启状态 默认标清模式开启
            //     'video_mode_status' => ConfigService::get('power', 'video_mode_status_V2', YesNoEnum::YES),
            //     // 视频算力消耗数
            //     'video_power'       => ConfigService::get('power', 'video_power_V2', 2000),
            //     // 视频算力消耗数对应时长 分钟
            //     'video_time'        => ConfigService::get('power', 'video_time_V2', 1),
            // ],
            'V3' => [
                // 视频合成模式 默认标清模式
                'video_mode_title'  => ConfigService::get('power', 'video_mode_title_V3', "专业模式"),
                // 视频合成模式开启状态 默认标清模式开启
                'video_mode_status' => ConfigService::get('power', 'video_mode_status_V3', YesNoEnum::NO),
                // 视频算力消耗数
                'video_power'       => ConfigService::get('power', 'video_power_V3', 4000),
                // 视频算力消耗数对应时长 分钟
                'video_time'        => ConfigService::get('power', 'video_time_V3', 1),
            ],
        ];
        return $modes[$mode];
    }


    /**
     * @notes 设置数字人合成算力消耗配置
     * @param $params
     * @return bool
     * @author yfdong
     * @date 2024/11/17 23:55
     */
    public static function setAvatarConfig($params, $mode): bool
    {
        try {
            // 数字人合成模式自定义名称
            ConfigService::set('power', 'video_mode_title_' . $mode, $params['video_mode_title']);
            // 数字人合成模式状态
            ConfigService::set('power', 'video_mode_status_' . $mode, $params['video_mode_status']);
            // 数字人合成模式消耗算力
            ConfigService::set('power', 'video_power_' . $mode, $params['video_power']);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


}
