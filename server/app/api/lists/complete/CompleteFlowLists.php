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

namespace app\api\lists\complete;


use app\api\lists\BaseApiDataLists;
use app\common\model\complete\TenantCompleteFlow;
use app\common\service\FileService;
use app\common\model\voicerecord\TenantVoiceRecord;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\model\file\TenantFile;



/**
 * CompleteFlowLists列表
 * Class CompleteFlowLists
 * @package app\api\lists\complete
 */
class CompleteFlowLists extends BaseApiDataLists
{

    public function queryWhere()
    {
        // 指定用户
        $where[] = ['uid', '=', $this->userId];
        // 根据状态筛选
        if (isset($this->params['status']) && in_array($this->params['status'], ['0', '1', '2', '3', '4', '5' ])) {
            $where[] = ['status', '=', $this->params['status']];
        }
        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function lists(): array
    {
        $list = TenantCompleteFlow::query()
            ->where($this->queryWhere())
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['create_time' => 'desc'])
            ->select()
            ->toArray();
        return $list;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function count(): int
    {
        return TenantCompleteFlow::query()->where($this->queryWhere())->count();
    }

}