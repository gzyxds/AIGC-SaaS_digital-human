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

namespace app\tenantapi\lists\file;

use app\tenantapi\lists\BaseAdminDataLists;
use app\tenantapi\logic\FileLogic;
use app\common\lists\ListsSearchInterface;
use app\common\model\file\TenantFile;
use app\common\service\FileService;

/**
 * 文件列表
 * Class FileLists
 * @package app\tenantapi\lists\file
 */
class FileLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 文件搜索条件
     * @return \string[][]
     * @author 段誉
     * @date 2021/12/29 14:27
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type', 'source'],
            '%like%' => ['name']
        ];
    }

    /**
     * @notes 额外查询处理
     * @return array
     * @author 段誉
     * @date 2024/2/7 10:26
     */
    public function queryWhere(): array
    {
        $where = [];
        // 如果cid为0则为未分组
        if("0" === $this->params['cid']){
            $where[] = ['cid', '=', '0'];
        }
        if (!empty($this->params['cid'])) {
            $cateChild = FileLogic::getCateIds($this->params['cid']);
            $cateChild[] = (int)$this->params['cid'];
            $where[] = ['cid', 'in', $cateChild];
        }

        return $where;
    }


    /**
     * @notes 获取文件列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/12/29 14:27
     */
    public function lists(): array
    {
        $lists = (new TenantFile())->field(['id,cid,type,name,uri,create_time'])
            ->order('id', 'desc')
            ->where($this->searchWhere)
            ->where($this->queryWhere())
//            ->where('source', FileEnum::SOURCE_ADMIN)
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['url'] = FileService::getFileUrl($item['uri']);
        }

        return $lists;
    }


    /**
     * @notes 获取文件数量
     * @return int
     * @author 段誉
     * @date 2021/12/29 14:29
     */
    public function count(): int
    {
        return (new TenantFile())->where($this->searchWhere)
            ->where($this->queryWhere())
//            ->where('source', FileEnum::SOURCE_ADMIN)
            ->count();
    }


    /**
     * @notes 获取视频文件时长
     * @return int
     * @author 段誉
     * @date 2021/12/29 14:29
     */
    public function getVideoDuration($fileId)
    {
        $videoDuration = 0;
        $fileInfo = (new TenantFile())->field(['id,cid,type,name,uri,create_time'])
            ->where(['id', $fileId])
            ->findOrEmpty()
            ->toArray();
        if ($fileInfo) {
            $filUrl = FileService::getFileUrl($fileInfo['uri']);
            $videoDuration = (new FileService())->getVideoLengthFromUrl($filUrl);
        }
        return $videoDuration;
    }
}
