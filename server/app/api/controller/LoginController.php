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

use app\common\enum\LoginEnum;
use app\common\model\user\User;
use Exception;
use app\api\validate\{LoginAccountValidate, RegisterValidate, WebScanLoginValidate, WechatLoginValidate};
use app\api\logic\LoginLogic;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use think\response\Json;

/**
 * 登录注册
 * Class LoginController
 * @package app\api\controller
 */
class LoginController extends BaseApiController
{

    public array $notNeedLogin = ['oaAuthBind', 'checkRegister', 'register', 'account', 'logout', 'codeUrl', 'oaLogin', 'mnpLogin', 'getScanCode', 'scanLogin', 'qrcode', 'ticket', 'codeBindAccount'];


    public function checkRegister()
    {
        $account = $this->request->post('mobile');
        if (empty($account)) {
            return $this->fail('账号不能为空');
        }
        // 用户账号验证
        $where = ['mobile' => $account];

        $user = User::where($where)->findOrEmpty();
        if ($user->isEmpty()) {
            return $this->fail('用户不存在', [], 0, 0);
        }
        return $this->success('用户验证通过');
    }

    /**
     * @notes 注册账号
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/7 15:38
     */
    public function register()
    {
        $params = (new RegisterValidate())->post()->goCheck();
        $result = LoginLogic::register($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->data($result);
    }


    /**
     * @notes 账号密码/手机号密码/手机号验证码登录
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/16 10:42
     */
    public function account()
    {
        $params = (new LoginAccountValidate())->post()->goCheck();
        $result = LoginLogic::login($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->data($result);
    }


    /**
     * @notes 退出登录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 10:42
     */
    public function logout()
    {
        LoginLogic::logout($this->userInfo);
        return $this->success();
    }


    /**
     * @notes 获取微信请求code的链接
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/15 18:27
     */
    public function codeUrl()
    {
        $url = $this->request->get('url');
        $result = ['url' => LoginLogic::codeUrl($url)];
        return $this->success('获取成功', $result);
    }


    /**
     * @notes 公众号登录
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function oaLogin()
    {
        $params = (new WechatLoginValidate())->post()->goCheck('oa');
        $res = LoginLogic::oaLogin($params);
        if (false === $res) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('', $res);
    }


    /**
     * @notes 小程序-登录接口
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function mnpLogin()
    {
        $params = (new WechatLoginValidate())->post()->goCheck('mnpLogin');
        $res = LoginLogic::mnpLogin($params);
        if (false === $res) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('', $res);
    }


    /**
     * @notes 小程序绑定微信
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function mnpAuthBind()
    {
        $params = (new WechatLoginValidate())->post()->goCheck("wechatAuth");
        $params['user_id'] = $this->userId;
        $result = LoginLogic::mnpAuthLogin($params);
        if ($result === false) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('绑定成功', [], 1, 1);
    }


    /**
     * @notes 公众号绑定微信
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:48
     */
    public function oaAuthBind(): Json
    {
        $params = (new WechatLoginValidate())->post()->goCheck("wechatAuth");
        $params['user_id'] = $this->userId;
        $result = LoginLogic::oaAuthLogin($params);
        if ($result === false) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('绑定成功', $result, 1, 1);
    }


    /**
     * @notes 获取扫码地址
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/10/20 18:25
     */
    public function getScanCode()
    {
        $redirectUri = $this->request->get('url/s');
        $result = LoginLogic::getScanCode($redirectUri);
        if (false === $result) {
            return $this->fail(LoginLogic::getError() ?? '未知错误');
        }
        return $this->success('', $result);
    }


    /**
     * @notes 网站扫码登录
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/10/21 10:28
     */
    public function scanLogin()
    {
        $params = (new WebScanLoginValidate())->post()->goCheck();
        $result = LoginLogic::scanLogin($params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError() ?? '登录失败');
        }
        return $this->success('', $result);
    }


    /**
     * @notes 更新用户头像昵称
     * @return \think\response\Json
     * @author 段誉
     * @date 2023/2/22 11:15
     */
    public function updateUser()
    {
        $params = (new WechatLoginValidate())->post()->goCheck("updateUser");
        LoginLogic::updateUser($params, $this->userId);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 二维码登录
     * @return Json
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @author cjhao
     * @date 2024/5/30 14:40
     */
    public function qrcode(): Json
    {
        $requestCheck = LoginLogic::requestLimit(1, 15);
        if ($requestCheck === false) {
            return $this->fail('请求过于频繁，5分钟后重试。');
        }
        $params = $this->request->get();
        $result = LoginLogic::getLoginCode($this->userId, $params);
        if (false === $result) {
            return $this->fail(LoginLogic::getError());
        }
        return $this->success('', $result);
    }


    /**
     * @notes 扫码验证
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/7/25 15:37
     */
    public function ticket()
    {
        $key = $this->request->post('key');
        $channel = $this->request->post('channel', 'login');
        if ($channel == 'bind') {
            $result = LoginLogic::checkBindTicket($key);
        } else {
            $result = LoginLogic::checkTicket($key);
        }
        return $this->success($result['msg'], $result['data'], $result['code'], $result['show']);
    }


    /**
     * @notes 根据微信公众code获取对应绑定账号信息
     * @return Json
     * @author yfdong
     * @date 2025/01/13 21:26
     */
    public function codeBindAccount(): Json
    {
        $code = $this->request->post('code');
        try {
            $account = LoginLogic::codeBindAccount($code);
            return $this->success("获取成功", $account);
        } catch (Exception $e) {
            return $this->fail("获取失败" . $e->getMessage());
        }
    }

}