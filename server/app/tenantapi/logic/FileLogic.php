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

namespace app\tenantapi\logic;


use app\common\logic\BaseLogic;
use app\common\model\file\File;
use app\common\model\file\TenantFile;
use app\common\model\file\TenantFileCate;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\storage\Driver as StorageDriver;

/**
 * 文件逻辑层
 * Class FileLogic
 * @package app\tenantapi\logic
 */
class FileLogic extends BaseLogic
{
    /**
     * @notes 移动文件
     * @param $params
     * @author 张无忌
     * @date 2021/7/28 15:29
     */
    public static function move($params)
    {
        (new TenantFile())->whereIn('id', $params['ids'])
            ->update([
                'cid' => $params['cid'],
                'update_time' => time()
            ]);
    }

    /**
     * @notes 重命名文件
     * @param $params
     * @author 张无忌
     * @date 2021/7/29 17:16
     */
    public static function rename($params)
    {
        (new TenantFile())->where('id', $params['id'])
            ->update([
                'name' => $params['name'],
                'update_time' => time()
            ]);
    }

    /**
     * @notes 批量删除文件
     * @param $params
     * @author 张无忌
     * @date 2021/7/28 15:41
     */
    public static function delete($params)
    {
        $result = TenantFile::whereIn('id', $params['ids'])->select();
        $StorageDriver = new StorageDriver([
            'default' => ConfigService::get('storage', 'default', 'local'),
            'engine'  => ConfigService::get('storage') ?? ['local'=>[]],
        ]);
        foreach ($result as $item) {
            $StorageDriver->delete($item['uri']);
        }
        TenantFile::destroy($params['ids']);
    }

    /**
     * @notes 添加文件分类
     * @param $params
     * @author 张无忌
     * @date 2021/7/28 11:32
     */
    public static function addCate($params)
    {
        TenantFileCate::create([
            'type' => $params['type'],
            'pid' => $params['pid'],
            'name' => $params['name']
        ]);
    }

    /**
     * @notes 编辑文件分类
     * @param $params
     * @author 张无忌
     * @date 2021/7/28 14:03
     */
    public static function editCate($params)
    {
        TenantFileCate::update([
            'name' => $params['name'],
            'update_time' => time()
        ], ['id' => $params['id']]);
    }

    /**
     * @notes 删除文件分类
     * @param $params
     * @author 张无忌
     * @date 2021/7/28 14:21
     */
    public static function delCate($params)
    {
        $fileModel = new TenantFile();
        $cateModel = new TenantFileCate();

        $cateIds = self::getCateIds($params['id']);
        array_push($cateIds, $params['id']);

        // 删除分类及子分类
        $cateModel->whereIn('id', $cateIds)->update(['delete_time' => time()]);

        // 删除文件
        $fileIds = $fileModel->whereIn('cid', $cateIds)->column('id');

        if (!empty($fileIds)) {
            self::delete(['ids' => $fileIds]);
        }
    }


    /**
     * @notes 获取所有分类id
     * @param $parentId
     * @param array $cateArr
     * @return array
     * @author 段誉
     * @date 2024/2/7 15:03
     */
    public static function getCateIds($parentId, array $cateArr = []): array
    {
        $childIds = TenantFileCate::where(['pid' => $parentId])->column('id');

        if (empty($childIds)) {
            return $childIds;
        } else {
            $allChildIds = $childIds;
            foreach ($childIds as $childId) {
                $allChildIds = array_merge($allChildIds, static::getCateIds($childId, $cateArr));
            }
            return $allChildIds;
        }
    }

    /**
     * @notes 获取文件详情
     * @param mixed $voice_id
     * @return array
     * @author yfdong
     * @date 2024/10/30 22:25
     */
    public static function detail(mixed $fileId)
    {
        $fileInfo = TenantFile::query()->where($fileId)->findOrEmpty()->toArray();
        if(isset($fileInfo)){
            $fileInfo['url'] = FileService::getFileUrl($fileId['uri']);
        }
        return $fileInfo;
    }

}