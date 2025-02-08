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

namespace app\common\model\voicerecord;


use app\common\model\BaseModel;
use app\common\model\file\TenantFile;
use app\common\model\user\User;
use app\common\model\voice\TenantVoice;
use app\common\service\FileService;
use think\model\concern\SoftDelete;


/**
 * TenantVoiceRecord模型
 * Class TenantVoiceRecord
 * @package app\common\model\voicerecord
 */
class TenantVoiceRecord extends BaseModel
{
    use SoftDelete;

    protected $name = 'tenant_voice_record';
    protected $deleteTime = 'delete_time';


    /**
     * @notes 关联声音素材
     * @return \think\model\relation\HasOne
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function voice()
    {
        return $this->hasOne(TenantVoice::class, 'id', 'voice_id');
    }


    /**
     * @notes 关联声音素材
     * @return \think\model\relation\HasOne
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function file()
    {
        return $this->hasOne(TenantFile::class, 'id', 'file_id');
    }

    /**
     * @notes 关联用户信息
     * @return \think\model\relation\HasOne
     * @author yfdong
     * @date 2024/11/09 17:40
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