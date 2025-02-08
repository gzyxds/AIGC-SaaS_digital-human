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

namespace app\common\model\dept;

use app\common\model\auth\TenantAdminDept;
use app\common\model\BaseModel;
use think\Exception;
use think\model\concern\SoftDelete;


/**
 * 部门模型
 * Class Dept
 * @package app\common\model\article
 */
class TenantDept extends BaseModel
{

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    /**
     * @notes 初始化管理员部门信息
     * @param mixed $tenantId
     * @param mixed $managerId
     * @return void
     * @throws Exception
     * @author yfdong
     * @date 2024/09/12 21:43
     */
    public static function initialization(mixed $tenantId, mixed $managerId)
    {
        $field = "tenant_id,name,pid,sort,leader,mobile,status";
        $tenantDept = TenantDept::where(['tenant_id' => 0])->field($field)->findOrEmpty()->toArray();
        if($tenantDept){
            $tenantDept['tenant_id'] = $tenantId;
            $dept = TenantDept::create($tenantDept);
            //添加对应的部门关联关系
            $tenantAdminDept ['admin_id'] = $managerId;
            $tenantAdminDept ['dept_id'] = $dept['id'];
            TenantAdminDept::create($tenantAdminDept);
        }else{
            throw new Exception("部门模板缺失");
        }
    }

    /**
     * @notes 状态描述
     * @param $value
     * @param $data
     * @return string
     * @author 段誉
     * @date 2022/5/25 18:03
     */
    public function getStatusDescAttr($value, $data)
    {
        return $data['status'] ? '正常' : '停用';
    }

}