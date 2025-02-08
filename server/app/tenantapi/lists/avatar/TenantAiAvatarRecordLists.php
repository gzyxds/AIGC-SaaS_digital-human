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

namespace app\tenantapi\lists\avatar;


use app\common\enum\YesNoEnum;
use app\common\lists\BaseDataLists;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\lists\ListsSearchInterface;
use app\common\model\file\TenantFile;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * TenantAiAvatarRecord列表
 * Class TenantAiAvatarRecordLists
 * @package app\tenantapi\listsavatar
 */
class TenantAiAvatarRecordLists extends BaseDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function setSearch(): array
    {
        return [];
    }

    /**
     * @notes 搜索条件
     * @return array
     * @author JXDN
     * @date 2024/12/10 16:31
     */
    public function queryWhere()
    {
        $where = [];

        if (!empty($this->params['user_info'])) {
            $where[] = ['u.sn|u.nickname|u.mobile|u.account', 'like', '%' . $this->params['user_info'] . '%'];
        }

        if (!empty($this->params['start_time'])) {
            $where[] = ['al.create_time', '>=', strtotime($this->params['start_time'])];
        }

        if (!empty($this->params['end_time'])) {
            $where[] = ['al.create_time', '<=', strtotime($this->params['end_time'])];
        }

        // 根据状态筛选
        if (isset($this->params['status']) && in_array($this->params['status'], ['0', '1', '2'])) {
            $where[] = ['al.status', '=', $this->params['status']];
        }

        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function lists(): array
    {
        $aiAvatarRecordList = TenantAiAvatarRecord::alias('al')
            ->join('user u', 'u.id = al.uid')
            ->where($this->searchWhere)
            ->where($this->queryWhere())
            ->with(['user', 'voice', 'video', 'file'])
            ->field(['u.sn', 'u.nickname', 'u.mobile', 'u.account', 'al.id', 'al.tenant_id', 'al.mode', 'al.uid', 'al.fail_reason', 'al.task_id', 'al.title', 'al.voice_id', 'al.video_id', 'al.cost_power', 'al.status', 'al.completion_time', 'al.cost_time', 'al.file_id', 'al.duration', 'al.size', 'al.cover', 'al.create_time'])
            ->order(['al.id' => 'desc'])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        $modes = [
            1 => ConfigService::get('power', 'video_mode_title_V1', "极速模式"),
            3 => ConfigService::get('power', 'video_mode_title_V3', "专业模式"),
        ];

        foreach ($aiAvatarRecordList as &$item) {
            // 模型通道
            if (isset($item['mode']) && !empty($item['mode'])) {
                $item['mode'] = $modes[$item['mode']];
            }

            // 合成音频
            if (isset($item['voice'])) {
                $file = (new TenantFile())->find($item['voice']['file_id']);
                if (!empty($file)) {
                    $item['voice']['voice_url'] = FileService::getFileUrl($file['uri']);
                } else {
                    $item['voice']['voice_url'] = '';
                }
            }

            // 数字形象
            if (isset($item['video'])) {
                $file = (new TenantFile())->find($item['video']['file_id']);
                if (!empty($file)) {
                    $item['video']['video_url'] = FileService::getFileUrl($file['uri']);
                } else {
                    $item['video']['video_url'] = '';
                }
            }

            // 合成结果文件处理
            if (isset($item['file'])) {
                $item['resultFile'] = FileService::getFileUrl($item['file']['uri']);
                unset($item['file']);
            }

            // 用户信息处理
            if (isset($item['user'])) {
                $item['userName'] = $item['user']['nickname'];
                $item['userAvatar'] = $item['user']['avatar'];
                unset($item['user']);
            }
        }
        return $aiAvatarRecordList;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function count(): int
    {
        return TenantAiAvatarRecord::alias('al')
            ->join('user u', 'u.id = al.uid')
            ->where($this->searchWhere)
            ->where($this->queryWhere())
            ->count();
    }

}