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


namespace app\api\controller\generate;


use app\api\controller\BaseApiController;
use app\api\lists\generate\GenerateLists;
use app\api\service\DoubaoService;
use app\api\validate\generate\GenerateValidate;
use app\common\model\generate\TenantGenerate;
use app\tenantapi\validate\avatar\TenantAiAvatarRecordValidate;

/**
 * AI文案生成控制器
 * Class GenerateController
 * @package app\api\controller\generate
 */
class GenerateController extends BaseApiController
{

    public array $notNeedLogin = [''];

    /**
     * @notes 获取列表
     * @return mixed
     * @author yfdong
     * @date 2024/12/23 00:20
     */
    public function lists()
    {
        return $this->dataLists(new GenerateLists());
    }

    /**
     * @notes 获取列表
     * @return mixed
     * @author yfdong
     * @date 2024/12/23 00:20
     */
    public function createAIContent(): mixed
    {
        $params = (new GenerateValidate())->post()->goCheck('add');
        try {
            $resultCopy = (new DoubaoService())->createAIContent($params);
            return $this->success("创建成功", ['result_copy' => $resultCopy]);
        } catch (\Exception $e) {
            return $this->fail("创建失败" . $e->getMessage());
        }
    }


}