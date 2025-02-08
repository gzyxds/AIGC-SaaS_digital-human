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

namespace app\common\model\voice;


use app\common\model\BaseModel;
use app\common\model\file\TenantFile;
use app\common\model\user\User;
use app\common\service\FileService;
use think\model\concern\SoftDelete;


/**
 * TenantVoice模型
 * Class TenantVoice
 * @package app\common\model\voice
 */
class TenantVoice extends BaseModel
{
    use SoftDelete;
    protected $name = 'tenant_voice';
    protected $deleteTime = 'delete_time';
    
    /**
     * @notes 关联克隆声素材文件
     * @return \think\model\relation\HasOne
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function file()
    {
        return $this->hasOne(TenantFile::class, 'id', 'file_id');
    }

    /**
     * @notes 用户信息关联
     * @return \think\model\relation\HasOne
     * @author yfdong
     * @date 2024/11/09 00:45
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }


    /**
     * @notes 封面获取器
     * @param $value
     * @return string
     * @author yfdong
     * @date 2024/10/28 23:48
     */
    public function getCoverAttr($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

}