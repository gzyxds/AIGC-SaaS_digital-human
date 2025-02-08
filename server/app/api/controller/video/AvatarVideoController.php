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


namespace app\api\controller\video;


use app\api\controller\BaseApiController;
use app\api\lists\video\AvatarVideoLists;
use app\api\logic\video\TenantVideoLogic;
use app\api\service\AvatarImageService;
use app\common\model\file\TenantFile;
use app\common\service\FileService;
use app\tenantapi\lists\file\FileLists;
use app\tenantapi\validate\video\TenantVideoValidate;
use think\facade\Log;


/**
 * TenantVideo控制器
 * Class TenantVideoController
 * @package app\tenantapi\controller\video
 */
class AvatarVideoController extends BaseApiController
{

    public array $notNeedLogin = ['receiveAvatarImage'];

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function lists()
    {
        return $this->dataLists(new AvatarVideoLists());
    }

    /**
     * 下载远程文件到本地
     *
     * @param string $remoteUrl 远程文件地址
     * @param string $localPath 本地保存路径
     * @throws \Exception
     */
    function downloadFile($remoteUrl, $localPath)
    {
        $fileContent = file_get_contents($remoteUrl);
        if ($fileContent === false) {
            throw new \Exception('无法下载远程文件: ' . $remoteUrl);
        }

        $result = file_put_contents($localPath, $fileContent);
        if ($result === false) {
            throw new \Exception('无法保存文件到本地: ' . $localPath);
        }
    }


    /**
     * @notes 数字人形象上传添加
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function add()
    {
        try {
            $params = (new TenantVideoValidate())->post()->goCheck('add');

            $params['uid'] = $this->userId;
            $params['terminal'] = $this->terminal;

            // 获取对应视频时长
            if (!isset($params['duration']) || $params['duration'] == null || $params['duration'] == 0) {
                $duration = FileService::getVideoDurationById($params['file_id']);
                $params['duration'] = $duration;
            }
            // 调用获取数字人模型创建接口
            $file = TenantFile::query()->where(['id' => $params['file_id']])->find()->toArray();
            $url = FileService::getFileUrl($file['uri']);
            $video_duration = (new FileService)->getDurationByUrl($url);
            if($video_duration < 30) {
                return $this->fail('视频时长不能小于30秒');
            }

            if($video_duration > 60 * 5) {
                return $this->fail('视频时长不能超过5分钟');
            }
            try {
                $result = (new AvatarImageService)->createAvatarImage(null, $params['name'], $url);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
            $params['image_id'] = $result['data']['mode_id'];
            $result = TenantVideoLogic::add($params);
            if (true === $result) {
                return $this->success('创建成功', [], 1, 1);
            } else {
                throw new \Exception(TenantVideoLogic::getError());
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }


    /**
     * @notes 接收数字人创建结果
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/12/18 22:07
     */
    public function receiveAvatarImage()
    {
        // 接收创建结果
        $param = (new TenantVideoValidate())->post()->goCheck('receive');
        Log::info('接受数字人V3创建训练模型结果：' . json_encode($param));
        $image = TenantVideoLogic::queryByImageId($param['mode_id']);
        if (!isset($image['id'])) {
            return $this->fail("对应任务在本系统不存在");
        }
        if ($param['errcode'] == 0) {
            TenantVideoLogic::updateVideoStetus($image['id'], true);
            Log::info('接受数字人V3创建训练模型结果为成功：' . $param['mode_id']);
        } else {
            TenantVideoLogic::updateVideoStetus($image['id'], false);
            Log::info('接受数字人V3创建训练模型结果为失败：' . $param['mode_id']);
        }
        return $this->success('创建训练模型结果接收成功', [], 1, 1);
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function edit()
    {
        $params = (new TenantVideoValidate())->post()->goCheck('edit');
        $result = TenantVideoLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantVideoLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function delete()
    {
        $params = (new TenantVideoValidate())->post()->goCheck('delete');
        TenantVideoLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function detail()
    {
        $params = (new TenantVideoValidate())->goCheck('detail');
        $result = TenantVideoLogic::detail($params);
        return $this->data($result);
    }


}
