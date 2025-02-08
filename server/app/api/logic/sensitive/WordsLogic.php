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

namespace app\api\logic\sensitive;


use app\common\logic\BaseLogic;
use app\common\model\sensitive\TenantSensitiveWords;


/**
 * 敏感词逻辑
 * Class TenantVoiceLogic
 * @package app\tenantapi\logic\voice
 */
class WordsLogic extends BaseLogic
{

    /**
     * @notes 获取全部在用敏感词
     * @param $params
     * @return array
     * @author yfdong
     */
    public function usedWords(): array
    {
        $words = TenantSensitiveWords::query()->where(['status' => 1])->select()->toArray();
        return $words;
    }

}