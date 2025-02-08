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

namespace app\common\model\complete;


use app\common\model\BaseModel;
use app\common\model\file\TenantFile;
use app\common\model\user\User;
use app\common\model\video\TenantVideo;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\service\FileService;
use think\model\concern\SoftDelete;


/**
 * TenantCompleteFlow模型
 * Class TenantCompleteFlow
 * @package \app\common\model\complete
 */
class TenantCompleteFlow extends BaseModel
{

    use SoftDelete;

    protected $name = 'tenant_complete_flow';

    protected $deleteTime = 'delete_time';

}