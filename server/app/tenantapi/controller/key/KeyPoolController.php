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

namespace app\tenantapi\controller\key;

use app\common\enum\ModuleEnum;
use app\tenantapi\controller\BaseAdminController;
use app\tenantapi\lists\key\KeyPoolLists;
use app\tenantapi\logic\key\KeyPoolLogic;
use app\tenantapi\validate\key\KeyPoolValidate;
use think\response\Json;

/**
 * Key池管理
 */
class KeyPoolController extends BaseAdminController
{

    /**
     * @notes Key池列表
     * @return Json
     * @author fzr
     */
    public function lists(): Json
    {
        return $this->dataLists((new KeyPoolLists()));
    }


    /**
     * @notes Key池新增
     * @return Json
     * @author fzr
     */
    public function add(): Json
    {
        $params = (new KeyPoolValidate())->post()->goCheck('add');
        $result = KeyPoolLogic::add($params);
        if ($result === false) {
            return $this->fail(KeyPoolLogic::getError());
        }
        return $this->success('添加成功');
    }

    /**
     * @notes Key池编辑
     * @return Json
     */
    public function edit(): Json
    {
        $params = (new KeyPoolValidate())->post()->goCheck();
        $result = KeyPoolLogic::edit($params);
        if ($result === false) {
            return $this->fail(KeyPoolLogic::getError());
        }
        return $this->success('编辑成功');
    }

    /**
     * @notes Key池删除
     * @return Json
     * @author fzr
     */
    public function del(): Json
    {
        $params = (new KeyPoolValidate())->post()->goCheck('id');
        $result = KeyPoolLogic::del(intval($params['id']));
        if ($result === false) {
            return $this->fail(KeyPoolLogic::getError());
        }
        return $this->success('删除成功');
    }


    /**
     * @notes Key池详情
     * @return Json
     * @author yfdong
     * @date 2024/11/18 23:49
     */
    public function detail(): Json
    {
        $params = (new KeyPoolValidate())->get()->goCheck('id');
        $detail = KeyPoolLogic::detail(intval($params['id']));
        return $this->data($detail);
    }


    /**
     * @notes Key池状态
     * @return Json
     * @author yfdong
     * @date 2024/11/18 23:49
     */
    public function status(): Json
    {
        $params = (new KeyPoolValidate())->post()->goCheck('id');
        $result = KeyPoolLogic::status(intval($params['id']));
        if ($result === false) {
            return $this->fail(KeyPoolLogic::getError());
        }
        return $this->success('操作成功');
    }

    /**
     * @notes  获取模块类型字典列表
     * @return Json
     * @author yfdong
     * @date 2024/11/19 20:55
     */
    public function moduleList(): Json
    {
        return $this->success('操作成功', ModuleEnum::getModuleDesc(true));
    }

}