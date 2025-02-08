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

namespace app\api\logic;

use app\common\cache\WebScanLoginCache;
use app\common\logic\AccountLogLogic;
use app\common\logic\BaseLogic;
use app\common\enum\LoginEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\model\user\User;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\common\service\wechat\WeChatConfigService;
use app\common\service\wechat\WeChatOaService;
use app\common\service\wechat\WeChatRequestService;
use app\tenantapi\logic\marketing\MarketingConfigLogic;
use EasyWeChat\OfficialAccount\Application;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use app\api\service\{UserTokenService, WechatUserService};
use app\common\enum\{user\AccountLogEnum, YesNoEnum};
use app\common\service\{
    wechat\WeChatMnpService
};
use app\common\model\user\{UserAuth};
use think\facade\{Cache, Db, Config, Log};

/**
 * 登录逻辑
 * Class LoginLogic
 * @package app\api\logic
 */
class LoginLogic extends BaseLogic
{

    /**
     * @notes 账号密码注册
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/7 15:37
     */
    public static function register(array $params)
    {
        try {
            // 如果传递了code参数，需要查询是否存在对应微信账号
            if (isset($params['code']) && $params['terminal'] == UserTerminalEnum::WECHAT_MMP) {
                $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
                //先检查openid是否有记录
                $isAuth = UserAuth::query()->where('openid', '=', $response['openid'])->findOrEmpty();
                if (!$isAuth->isEmpty()) {
                    throw new \Exception('该微信已被绑定');
                }
            }
            $userSn = User::createUserSn();
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $avatar = ConfigService::get('default_image', 'user_avatar');

            $mobile = '';
            if ($params['scene'] == LoginEnum::MOBILE_CAPTCHA) {
                $mobile = $params['account'];
            }

            User::create([
                'sn'        => $userSn,
                'tenant_id' => request()->tenantId,
                'avatar'    => $avatar,
                'nickname'  => '用户' . $userSn,
                'account'   => $params['account'],
                'password'  => $password,
                'mobile'    => $mobile,
                'channel'   => $params['channel'],
            ]);

            // 账号/手机号 密码登录
            $where = ['account|mobile' => $params['account']];

            $user = User::where($where)->findOrEmpty();
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            // 如果传递了code参数并且有对应授权则增加微信授权记录
            if (isset($params['code']) && $params['terminal'] == UserTerminalEnum::WECHAT_MMP) {
                $response['user_id'] = $user->id;
                $response['terminal'] = UserTerminalEnum::WECHAT_MMP;
                self::createAuth($response);
            }

            //更新登录信息
            $user->login_time = time();
            $user->login_ip = request()->ip();
            $user->save();

            // 用户赠送流水
            $config = MarketingConfigLogic::getGiftConfig();
            // 租户开启了用户注册赠送算力
            if (1 == $config['gift_switch']) {
                // 增加用户累计充值金额及用户余额
                $user = User::findOrEmpty($user->id);
                $user->total_recharge_amount += $config['gift_amount'];
                $user->user_money += $config['gift_amount'];
                $user->save();

                // 记录账户流水
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_INC_REGISTER,
                    AccountLogEnum::INC,
                    $config['gift_amount'],
                    '',
                    '用户注册赠送'
                );
            }
            //设置token
            $userInfo = UserTokenService::setToken($user, $params['terminal']);
            //返回登录信息
            $avatar = $user->avatar ?: Config::get('project.default_image.user_avatar');
            $avatar = FileService::getFileUrl($avatar);

            return [
                'nickname' => $userInfo['nickname'],
                'sn'       => $userInfo['sn'],
                'mobile'   => $userInfo['mobile'],
                'avatar'   => $avatar,
                'token'    => $userInfo['token'],
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 账号/手机号登录，手机号验证码
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/6 19:26
     */
    public static function login($params)
    {
        try {
            // 账号/手机号 密码登录
            $where = ['account' => $params['account']];
            if ($params['scene'] == LoginEnum::MOBILE_CAPTCHA) {
                //手机验证码登录
                $where = ['mobile' => $params['account']];
            }
            if ($params['scene'] == LoginEnum::ACCOUNT_PASSWORD) {
                $where = ['mobile|account' => $params['account']];
            }

            $user = User::query()->where($where)->findOrEmpty();
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            //更新登录信息
            $user->login_time = time();
            $user->login_ip = request()->ip();
            $user->save();

            //设置token
            $userInfo = UserTokenService::setToken($user, $params['terminal']);

            //返回登录信息
            $avatar = $user->avatar ?: Config::get('project.default_image.user_avatar');
            $avatar = FileService::getFileUrl($avatar);

            return [
                'nickname' => $userInfo['nickname'],
                'sn'       => $userInfo['sn'],
                'mobile'   => $userInfo['mobile'],
                'avatar'   => $avatar,
                'token'    => $userInfo['token'],
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 退出登录
     * @param $userInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 17:56
     */
    public static function logout($userInfo)
    {
        //token不存在，不注销
        if (!isset($userInfo['token'])) {
            return false;
        }

        //设置token过期
        return UserTokenService::expireToken($userInfo['token']);
    }


    /**
     * @notes 获取微信请求code的链接
     * @param string $url
     * @return string
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function codeUrl(string $url)
    {
        return (new WeChatOaService())->getCodeUrl($url);
    }


    /**
     * @notes 公众号登录
     * @param array $params
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function oaLogin(array $params)
    {
        $modelUser = new User();
        $modelUser->startTrans();
        try {
            $terminal = UserTerminalEnum::WECHAT_OA;
            if (!empty($params['key'])) {
                $terminal = UserTerminalEnum::PC;

                $key_data = cache($params['key']);
                if (!$key_data || $key_data['is_login'] == 1) {
                    self::$error = '登录信息已失效';
                    return false;
                }
            }

            //通过code获取微信 openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $userServer = new WechatUserService($response, $terminal);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            // 设置PC缓存token并标记已登录
            if (!empty($params['key'])) {
                self::setIsLogin($params['key'], $userInfo['id'], 1);
            }

            $modelUser->commit();
            return $userInfo;
        } catch (Exception $e) {
            $modelUser->rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-静默登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function silentLogin(array $params)
    {
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo('silent')->getUserInfo();

            if (!empty($userInfo)) {
                // 更新登录信息
                self::updateLoginInfo($userInfo['id']);
            }

            return $userInfo;
        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-授权登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function mnpLogin(array $params)
    {
        Db::startTrans();
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();
            // 如果传递手机号参数进行绑定手机号操作
            if (isset($params['mobile'])) {
                User::update([
                    'mobile' => $params['mobile'],
                ], ['id' => $userInfo['id']]);
            }
            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;
        } catch (\Exception  $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 更新登录信息
     * @param $userId
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function updateLoginInfo($userId)
    {
        $user = User::findOrEmpty($userId);
        if ($user->isEmpty()) {
            throw new \Exception('用户不存在');
        }

        $time = time();
        $user->login_time = $time;
        $user->login_ip = request()->ip();
        $user->update_time = $time;
        $user->save();
    }


    /**
     * @notes 小程序端绑定微信
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function mnpAuthLogin(array $params)
    {
        try {
            //通过code获取微信openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $response['user_id'] = $params['user_id'];
            $response['terminal'] = UserTerminalEnum::WECHAT_MMP;

            return self::createAuth($response);

        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 公众号端绑定微信
     * @param array $params
     * @return bool|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function oaAuthLogin(array $params)
    {
        try {
            $userId = $params['user_id'];
            $terminal = UserTerminalEnum::WECHAT_OA;
            if (!empty($params['key'])) {
                $terminal = UserTerminalEnum::PC;
                $key_data = cache($params['key']);
                if (!$key_data || $key_data['channel'] != 'bind' || $key_data['is_bind'] == 1) {
                    self::$error = '绑定信息已失效';
                    return false;
                }
                $userId = $key_data['user_id'];
            }
            //通过code获取微信openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $response['user_id'] = $userId;
            $response['terminal'] = $terminal;

            // 创建授权
            self::createAuth($response,$params['key'] ?? '');
            // 标记已绑定
            if (!empty($params['key'])) {
                self::setIsLogin($params['key'], $userId, 0, 1);
            }

            $userInfo = (new User())->field('nickname, avatar')
                ->where('id', $userId)
                ->findOrEmpty()
                ->toArray();

            return $userInfo;
        } catch (Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 生成授权记录
     * @param $response
     * @return bool
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function createAuth($response, $key = ''): bool
    {
        //先检查openid是否有记录
        $isAuth = (new UserAuth())->where('openid', '=', $response['openid'])->findOrEmpty();
        if (!$isAuth->isEmpty()) {
            if(!empty($key)) {
                self::setScanError($key, '该微信已被绑定');
            }
        }

        if (isset($response['unionid']) && !empty($response['unionid'])) {
            //在用unionid找记录，防止生成两个账号，同个unionid的问题
            $userAuth = (new UserAuth())->where(['unionid' => $response['unionid']])
                ->findOrEmpty();
            if (!$userAuth->isEmpty() && $userAuth->user_id != $response['user_id']) {
                if(!empty($key)) {
                    self::setScanError($key,'该微信已被绑定');
                }
                throw new Exception('该微信已被绑定');
            }
        }
        try {
            //如果没有授权，直接生成一条微信授权记录
            UserAuth::create([
                'user_id'  => $response['user_id'],
                'openid'   => $response['openid'],
                'unionid'  => $response['unionid'] ?? '',
                'terminal' => $response['terminal']
            ]);
        } catch (Exception $e) {
            if(!empty($key)) {
                self::setScanError($key,'该微信已被绑定');
            }
            throw new Exception('该微信已被绑定');
        }
        return true;
    }


    /**
     * @notes 获取扫码登录地址
     * @return array|false
     * @author 段誉
     * @date 2022/10/20 18:23
     */
    public static function getScanCode($redirectUri)
    {
        try {
            $config = WeChatConfigService::getOpConfig();
            $appId = $config['app_id'];
            $redirectUri = UrlEncode($redirectUri);

            // 设置有效时间标记状态, 超时扫码不可登录
            $state = MD5(time() . rand(10000, 99999));
            (new WebScanLoginCache())->setScanLoginState($state);

            // 扫码地址
            $url = WeChatRequestService::getScanCodeUrl($appId, $redirectUri, $state);
            return ['url' => $url];

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 网站扫码登录
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/10/21 10:28
     */
    public static function scanLogin($params)
    {
        Db::startTrans();
        try {
            // 通过code 获取 access_token,openid,unionid等信息
            $userAuth = WeChatRequestService::getUserAuthByCode($params['code']);

            if (empty($userAuth['openid']) || empty($userAuth['access_token'])) {
                throw new \Exception('获取用户授权信息失败');
            }

            // 获取微信用户信息
            $response = WeChatRequestService::getUserInfoByAuth($userAuth['access_token'], $userAuth['openid']);

            // 生成用户或更新用户信息
            $userServer = new WechatUserService($response, UserTerminalEnum::PC);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 更新用户信息
     * @param $params
     * @param $userId
     * @return User
     * @author 段誉
     * @date 2023/2/22 11:19
     */
    public static function updateUser($params, $userId)
    {
        return User::where(['id' => $userId])->update([
            'nickname'    => $params['nickname'],
            'avatar'      => FileService::setFileUrl($params['avatar']),
            'is_new_user' => YesNoEnum::NO,
        ]);
    }


    /**
     * @notes 接口请求限制
     * @param int $time 分钟
     * @param int $limit 限制次数
     * @return bool
     * @author mjf
     * @date 2024/1/18 11:06
     */
    public static function requestLimit(int $time = 1, int $limit = 5)
    {
        $limitTime = $time * 60;
        //获取访问用户的IP
        $ip = md5(request()->ip());
        $path = request()->controller() . '/' . request()->action();
        $cacheKey = md5($ip . $path);
        //每个IP和接口每分钟不能超过的次数
        if (!Cache::has($cacheKey)) {
            Cache::set($cacheKey, 1, $limitTime);
            return true;
        } else {
            $cacheIpCount = Cache::get($cacheKey) ?: 0;
            if ($cacheIpCount > $limit) {
                return false;
            } else {
                Cache::inc($cacheKey, 1);
                return true;
            }
        }
    }


    /**
     * @notes 获取公众号带参二维码
     * @param array $params
     * @return array|false
     * @throws TransportExceptionInterface
     * @author 段誉
     * @date 2021/10/21 15:02
     */
    public static function getLoginCode($userId, array $params = []): bool|array
    {
        try {
            $config = WeChatConfigService::getOaConfig();
            $app = new Application($config);
            $api = $app->getClient();

            $accessToken = $app->getAccessToken();
            $access_token = $accessToken->getToken();

            $inviteCode = $params['invite_code'] ?? '';
            $channel = $params['channel'] ?? 'login';
            $key = self::setKey($inviteCode, $channel, $userId)['key'];
            $create_qrcode = $api->post('/cgi-bin/qrcode/create?access_token=' . $access_token, [
                'body' => \json_encode([
                    "expire_seconds" => 120,
                    "action_name"    => 'QR_STR_SCENE',
                    "action_info"    => ['scene' => ['scene_str' => $key]],
                ]),
            ]);
            $create_qrcode = json_decode($create_qrcode, true);
            if (!isset($create_qrcode['ticket'])) {
                if (isset($create_qrcode['errcode']) == 40001) {
                    //刷新access_token
                    $access_token = $accessToken->refresh();

                    $create_qrcode = $api->post('/cgi-bin/qrcode/create?access_token=' . $access_token, [
                        'body' => \json_encode([
                            "expire_seconds" => 120,
                            "action_name"    => 'QR_STR_SCENE',
                            "action_info"    => ['scene' => ['scene_str' => $key]],
                        ]),
                    ]);
                    $create_qrcode = json_decode($create_qrcode, true);

                    if (!isset($create_qrcode['ticket'])) {
                        Log::write('二维码生成失败：' . json_encode($create_qrcode));
                        throw new \Exception("生成失败！请稍后重试");
                    }
                } else {
                    Log::write('二维码生成失败：' . json_encode($create_qrcode));
                    throw new \Exception("生成失败！请稍后重试");
                }
            }

            return [
                'expire_seconds' => 120,
                'url'            => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $create_qrcode['ticket'],
                'key'            => $key,
                'ticket'         => $create_qrcode['ticket'],
            ];

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 设置当前用户key
     * @return array
     * @author 段誉
     * @date 2021/10/21 14:49
     */
    public static function setKey($inviteCode, $channel = 'login', $userId = 0): array
    {
        $unid = uniqid();
        $ip = request()->ip();
        session_start();
        $session_id = session_id();
        $temp_key = md5($session_id . $ip . $unid);

        $data = [
            "key"         => $temp_key,
            'channel'     => $channel,
            "status"      => 0,
            "session_id"  => $session_id,
            "salt"        => $unid,
            "create_time" => time(),
            "ip"          => $ip,
            'is_scan'     => 0,
            'is_login'    => 0,
            'is_bind'     => 0, // 扫码绑定微信时使用
            'token'       => [],
            'user_id'     => $userId,
            'invite_code' => $inviteCode,
        ];
        cache($temp_key, $data, 120);
        return $data;
    }


    /**
     * @notes 验证是否已扫码
     * @param $key
     * @return array
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function checkBindTicket($key)
    {
        try {
            $data = [
                'status' => 0,// 未扫码-1 未绑定-2 绑定信息异常-3 登录成功-4
            ];

            if (empty($key)) {
                throw new \Exception('参数缺失');
            }

            $key_info = cache($key);
            if (empty($key_info)) {
                throw new \Exception('绑定信息不存在');
            }

            if ($key_info['is_scan'] != 1) {
                // 未扫码
                $data['status'] = 1;
                return ['data' => $data, 'msg' => '未扫码', 'code' => 1, 'show' => 0];
            }

            if ($key_info['status'] != 1) {
                if (!empty($key_info['error'])) {
                    $data['status'] = 3;
                    throw new Exception($key_info['error']);
                }
                $data['status'] = 2;
                return ['data' => $data, 'msg' => '已扫码未绑定', 'code' => 1, 'show' => 0];
            }

            if ($key_info['is_scan'] == 1 && $key_info['is_bind'] == 1) {
                $data['status'] = 4;
            } else {
                $data['status'] = 3;
                throw new Exception('绑定失败');
            }

            return ['data' => $data, 'msg' => '', 'code' => 1, 'show' => 0];

        } catch (\Exception $e) {
            return ['data' => $data, 'msg' => $e->getMessage(), 'code' => 0, 'show' => 1];
        }
    }


    /**
     * @notes 验证是否已扫码
     * @param $key
     * @return array
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function checkTicket($key)
    {
        try {
            $data = [
                'user'   => [],
                'status' => 0,// 未扫码-1 未登录-2 登录信息异常-3 登录成功-4
            ];

            if (empty($key)) {
                throw new \Exception('参数缺失');
            }

            $key_info = cache($key);
            if (empty($key_info)) {
                return ['data' => $data, 'msg' => '登录信息不存在', 'code' => 0, 'show' => 0];
            }

            if ($key_info['is_scan'] != 1) {
                // 未扫码
                $data['status'] = 1;
                return ['data' => $data, 'msg' => '未扫码', 'code' => 1, 'show' => 0];
            }

            if ($key_info['status'] != 1) {
                $data['status'] = 2;
                return ['data' => $data, 'msg' => '已扫码未登录', 'code' => 1, 'show' => 0];
            }

            // 加上cdn后，ip校验有问题，暂时关闭
//            if ($key_info['ip'] !== request()->ip()) {
//                $data['status'] = 3;
//                return ['data' => $data, 'msg' => '登录信息错误','code' => 1, 'show' => 0];
//            }

            if ($key_info['status'] == 1 && $key_info['is_scan'] == 1 && $key_info['is_login'] == 0 && isset($key_info['user_id'])) {
                //已扫码已登录，未获取token，用于PC端微信扫码已授权情况下的token获取及清除旧缓存
                $key_info['token'] = UserTokenService::setToken($key_info['user_id'], UserTerminalEnum::PC);
            }

            // 返回token值
            $data['user'] = $key_info['token'] ?? '';
            $data['status'] = 4;
            return ['data' => $data, 'msg' => '', 'code' => 1, 'show' => 0];

        } catch (\Exception $e) {
            return ['data' => $data, 'msg' => $e->getMessage(), 'code' => 0, 'show' => 1];
        }
    }


    /**
     * @notes 设置错误信息
     * @param $key
     * @param string $error
     * @return bool
     * @author mjf
     * @date 2024/6/3 11:43
     */
    public static function setScanError($key, string $error = ''): bool
    {
        if (empty($key)) {
            return false;
        }

        $cacheData = cache($key);

        // 更新错误信息
        if (!empty($error)) {
            $cacheData['error'] = $error;
        }

        cache($key, $cacheData, 120);
        return true;
    }

    /**
     * @notes 设置为已登录状态
     * @param $key
     * @param $user
     * @return bool
     * @author 段誉
     * @date 2022/6/1 15:10
     */
    public static function setIsLogin($key, $user_id, $is_login = 0, $is_bind = 0)
    {
        if (empty($key)) {
            return false;
        }

        $cacheData = cache($key);

        $token = '';
        if ($cacheData['channel'] == 'login' && $is_login == 1) {
            $token = UserTokenService::setTokenById($user_id, UserTerminalEnum::PC);
        }

        $cacheData['token'] = $token;
        $cacheData['status'] = 1;
        $cacheData['is_login'] = $is_login;
        $cacheData['is_bind'] = $is_bind;
        $cacheData['user_id'] = $user_id;

        cache($key, $cacheData, 120);
        return true;
    }


    /**
     * @notes 根据微信小程序code获取对应用户信息
     * @param $code
     * @return array
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @author yfdong
     * @date 2025/01/13 21:33
     */
    public static function codeBindAccount($code)
    {
        try {
            $account = ['id' => ''];
            //通过code获取微信openid
            $response = (new WeChatMnpService())->getMnpResByCode($code);
            //先检查openid是否有记录
            $isAuth = UserAuth::query()->where('openid', '=', $response['openid'])->findOrEmpty();
            if (!$isAuth->isEmpty()) {
                $account = User::query()->where('id', '=', $isAuth->user_id)->findOrEmpty()->toArray();
            }
            return $account;
        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return ['id' => ''];
        }
    }
}