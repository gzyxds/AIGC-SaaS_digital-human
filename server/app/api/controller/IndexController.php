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

namespace app\api\controller;


use app\api\logic\IndexLogic;
use app\common\service\ConfigService;
use think\response\Json;


/**
 * index
 * Class IndexController
 * @package app\api\controller
 */
class IndexController extends BaseApiController
{


    public array $notNeedLogin = ['index', 'config', 'policy', 'allPolicy', 'decorate', 'customer', 'guideConfig'];


    /**
     * @notes 首页数据
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public function index()
    {
        $result = IndexLogic::getIndexData();
        return $this->data($result);
    }


    /**
     * @notes 全局配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:41
     */
    public function config()
    {
        $result = IndexLogic::getConfigData();
        return $this->data($result);
    }


    /**
     * @notes 政策协议
     * @return Json
     * @author 段誉
     * @date 2022/9/20 20:00
     */
    public function policy()
    {
        $type = $this->request->get('type/s', '');
        $result = IndexLogic::getPolicyByType($type);
        return $this->data($result);
    }


    /**
     * @notes 获取政策协议
     * @return Json
     * @author yfdong
     * @date 2024/11/22 00:10
     */
    public function allPolicy()
    {
        $result = ConfigService::getTypeAll('agreement');
        $newArray = [];
        if (sizeof($result) > 0) {
            foreach ($result as $item) {
                $parts = explode('_', $item['name']);
                if (count($parts) == 2) {
                    $category = $parts[0];
                    $type = $parts[1];
                    $hasContain = false;
                    $i = 0;
                    foreach ($newArray as $index => $new) {
                        if ($category == $new['type']) {
                            $hasContain = true;
                            $i = $index;
                        }
                    }
                    if (!$hasContain) {
                        $newArray[] = [
                            'title'       => $type == 'title' ? $item['value'] : '',
                            'type'        => $category,
                            'content'     => $type == 'content' ? $item['value'] : '',
                            'update_time' => $item['update_time'],
                        ];
                    } else {
                        $newArray[$i][$type] = $item['value'];
                    }
                }
            }
        }
        return $this->data($newArray);
    }


    /**
     * @notes 装修信息
     * @return Json
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public function decorate()
    {
        $type = $this->request->get('type/d');
        $result = IndexLogic::getDecorate($type);
        return $this->data($result);
    }

    /**
     * @notes 获取客服配置
     * @return Json
     * @author yfdong
     * @date 2024/11/29 23:16
     */
    public function customer(): Json
    {
        $result = IndexLogic::getCustomerConfig();
        return $this->data($result);
    }

    /**
     * @notes 获取充值指引配置
     * @return Json
     * @author yfdong
     * @date 2025/01/20 23:34
     */
    public function guideConfig(): Json
    {
        $result = IndexLogic::getGuideSetting();
        return $this->success("获取成功", $result);
    }


}