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

namespace app\tenantapi\validate\setting;

use app\common\validate\BaseValidate;

/**
 * 网站设置验证器
 * Class WebSettingValidate
 * @package app\tenantapi\validate\setting
 */
class PowerSettingValidate extends BaseValidate
{
    protected $rule = [
        'mode'              => 'require|in:1,2,3',
        'video_power'       => 'require',
        'video_mode_status' => 'require',
        'video_mode_title'  => 'require',
        'voice_power'       => 'require',
        'voice_copy'        => 'require',
        'upload_file'       => 'require',
        'clone_power'       => 'require',
    ];

    protected $message = [
        'mode.require'              => '请选择数字人视频生成模式',
        'mode.in'                   => '数字人视频生成模式类型错误 ',
        'video_power.require'       => '请配置数字人视频生成消耗算力',
        'video_mode_status.require' => '请配置数字人视频生成模式状态',
        'video_mode_title.require'  => '请配置数字人视频生成模式自定义名称',
        'voice_power.require'       => '请配置声音合成消耗算力',
        'voice_copy'                => '请配置声音合成示例文案',
        'upload_file'               => '请配置是否开启上传声音文件',
        'clone_power.require'       => '请配置音色克隆消耗算力',
    ];

    protected $scene = [
        'videoGet' => ['mode'],
        'videoSet' => ['mode', 'video_power', 'video_mode_status', 'video_mode_title'],
        'voice'    => ['voice_power', 'upload_file'],
        'clone'    => ['clone_power', 'voice_copy'],
    ];
}