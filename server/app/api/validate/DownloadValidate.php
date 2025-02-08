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

namespace app\api\validate;

use app\common\validate\BaseValidate;

/**
 * 邮件发送参数验证
 */
class DownloadValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'format' => 'require',
        'url' => 'require',
    ];

    protected $message = [
        'name.require' => '请输入文件名称',
        'format.require' => '请输入文件格式',
        'url.require' => '请输入文件网络地址',
    ];

    public function sceneDownload(): DownloadValidate
    {
        return $this->only(['name', 'format', 'url']);
    }
}