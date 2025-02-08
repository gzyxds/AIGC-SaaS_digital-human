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

namespace app\tenantapi\validate\complete;


use app\common\validate\BaseValidate;

/**
 * FlowValidate验证器
 * Class FlowValidate
 * @package app\api\validate\flow
 */
class FlowValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id'              => 'require',
        'voice_id'        => 'require',
        'voice_mode'      => 'require',
        'content'         => 'require',
        'timbre'          => 'require',
        'voice_record_id' => 'require',
        'video_mode'      => 'require',
        'video_id'        => 'require',
        'video_file_id'   => 'require',
        'video_name'      => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id'              => 'id',
        'voice_id'        => '声音模型id',
        'voice_mode'      => '声音模型',
        'content'         => '内容',
        'timbre'          => '音色名称',
        'voice_record_id' => '声音合成记录id',
        'video_mode'      => '视频合成模型',
        'video_id'        => '数字人形象id',
        'video_file_id'   => '数字人文件id',
        'video_name'      => '数字人合成结果标题',
    ];


    /**
     * 添加场景
     * @return FlowValidate
     */
    public function sceneAdd()
    {
        return $this->only(['voice_id', 'voice_mode', 'content', 'video_mode', 'video_name']);
    }


    /**
     * 删除场景
     * @return FlowValidate
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * 详情场景
     * @return FlowValidate
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}