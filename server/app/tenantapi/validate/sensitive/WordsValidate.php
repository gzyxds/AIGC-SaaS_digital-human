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

namespace app\tenantapi\validate\sensitive;


use app\common\validate\BaseValidate;


/**
 * 敏感词验证器
 * Class WordsValidate
 * @package app\tenantapi\validate\voice
 */
class WordsValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'words' => 'require|checkWord',
        'status' => 'require',
        'sensitive_mode' => 'require',
        'sensitive_switch' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id.require' => 'id不能为空',
        'words' => '敏感词',
        'status' => '状态',
        'sensitive_mode' => '敏感词模式',
        'sensitive_switch' => '敏感词词库匹配',
    ];


    /**
     * @notes 添加场景
     * @return WordsValidate
     * @author yfdong
     */
    public function sceneAdd()
    {
        return $this->only(['words', 'status']);
    }


    /**
     * @notes 编辑场景
     * @return WordsValidate
     * @author yfdong
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'words', 'status']);
    }


    /**
     * @notes 删除场景
     * @return WordsValidate
     * @author yfdong
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return WordsValidate
     * @author yfdong
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 校验格式
     * @param $value
     * @return string|true
     * @author yfdong
     * @date 2024/11/19 23:57
     */
    function checkWord($value)
    {
        // 分割字符串成数组
        $items = explode('、', $value);

        // 检查每个项是否为空
        foreach ($items as $item) {
            if (trim($item) === '') {
                return '格式不符合';
            }
        }

        return true;
    }


    /**
     * @notes 敏感词设置场景
     * @return WordsValidate
     * @author yfdong
     * @date 2024/11/20 00:19
     */
    public function sceneSetting()
    {
        return $this->only(['sensitive_switch', 'sensitive_mode']);
    }


}