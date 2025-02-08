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

namespace app\common\service;

use app\common\enum\notice\NoticeEnum;
use app\common\enum\notice\SmsEnum;
use app\common\enum\YesNoEnum;
use app\common\model\notice\EmailLog;
use app\common\model\notice\NoticeSetting;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    /**
     * 错误信息
     */
    protected mixed $error = null;

    /**
     * 配置信息
     */
    protected array $config = [];

    /**
     * 构造函数
     * @throws Exception
     */
    public function __construct()
    {
        $config = [
            'from_name'     => ConfigService::get('website', 'pc_title'),    // 发件人昵称
            'from_address'  => ConfigService::get('email', 'form_address'),  // 发件人邮箱地址
            'auth_password' => ConfigService::get('email', 'auth_password'), // SMTP服务授权密码
            'smtp_host'     => ConfigService::get('email', 'smtp_host'),     // SMTP服务器
            'smtp_port'     => ConfigService::get('email', 'smtp_port'),     // SMTP服务器端口
        ];

        if (!$config['from_address'] || !$config['auth_password'] || !$config['smtp_host'] || !$config['smtp_port']) {
            throw new Exception('邮箱服务配置错误');
        }

        $this->config = $config;
    }

    /**
     * @notes 发送邮件
     * @param $email //邮箱地址
     * @param $title //邮件主题
     * @param $content //邮件内容
     * @return bool|string
     * @throws \PHPMailer\PHPMailer\Exception
     * @author ljj
     * @date 2023/7/19 2:18 下午
     */
    public function sendEmail($email, $title, $content): bool|string
    {
        // 实例化
        $mail = new PHPMailer();
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        // $mail->SMTPDebug = 1;
        // 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        // smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        // SMTP服务器
        $mail->Host = $this->config['smtp_host'];
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = $this->config['smtp_port'];
        // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
        // 设置发件人昵称
        $mail->FromName = $this->config['from_name'];
        // smtp登录的账号
        $mail->Username = $this->config['from_address'];
        // SMTP服务授权密码
        $mail->Password = $this->config['auth_password'];
        // 设置发件人邮箱地址
        $mail->From = $this->config['from_address'];
        // 邮件正文是否为html编码
        $mail->isHTML(true);
        // 设置收件人邮箱地址
        $mail->addAddress($email);
        //添加该邮件的主题
        $mail->Subject = $title;
        //添加邮件正文
        $mail->Body = $content;

        $result = $mail->send();
        if ($result) {
            return true;
        }
        return $mail->ErrorInfo;
    }

    /**
     * @notes 发送邮件验证码
     * @param $email
     * @param $scene_id
     * @return mixed
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     * @author ljj
     * @date 2023/7/19 2:29 下午
     */
    public function sendCode($email, $scene_id): mixed
    {
        //限制发送次数
        $emailLog = (new EmailLog())
            ->where(['email'=>$email,'send_status'=>SmsEnum::SEND_SUCCESS,'scene_id'=>$scene_id])
            ->order('send_time', 'desc')
            ->findOrEmpty()
            ->toArray();
        if(!empty($emailLog) && ($emailLog['send_time'] > time() - 60)) {
            throw new Exception('同一邮箱1分钟只能发送1次邮件');
        }

        //获取场景配置
        $noticeSetting = (new NoticeSetting())->where('scene_id', $scene_id)->findOrEmpty()->toArray();
        if (empty($noticeSetting)) {
            throw new Exception('找不到对应场景的配置');
        }

        if (!isset($noticeSetting['email_notice']['status']) || $noticeSetting['email_notice']['status'] == YesNoEnum::NO) {
            throw new Exception('通知场景状态错误');
        }

        if (!isset($noticeSetting['email_notice']['title']) || empty($noticeSetting['email_notice']['title']) || !isset($noticeSetting['email_notice']['content']) || empty($noticeSetting['email_notice']['content'])) {
            throw new Exception('通知场景内容缺失');
        }

        // 邮件主题
        $title = $noticeSetting['email_notice']['title'];

        // 验证码
        $code = mt_rand(100000,999999);

        // 替换邮件正文
        $content = str_replace('${code}',$code,$noticeSetting['email_notice']['content']);

        //发送邮件
        $result = $this->sendEmail($email,$title,$content);

        //添加发送记录
        EmailLog::create([
            'scene_id'    => $scene_id,
            'email'       => $email,
            'content'     => $content,
            'code'        => $code,
            'send_status' => $result === true ? 1 : 2,
            'send_time'   => time(),
            'results'     => $result === true ? '' : $result,
        ]);

        return $result;
    }

    /**
     * @notes 校验验证码
     * @param $email
     * @param $code
     * @param int $sceneId
     * @return bool
     * @author ljj
     * @date 2023/7/19 10:56 上午
     */
    public function verify($email, $code, int $sceneId = 0): bool
    {
        $where = [
            ['email', '=', $email],
            ['send_status', '=', SmsEnum::SEND_SUCCESS],
            ['scene_id', 'in', NoticeEnum::SMS_SCENE],
            ['is_verify', '=', YesNoEnum::NO],
        ];

        if (!$sceneId) {
            $where[] = ['scene_id', '=', $sceneId];
        }

        $emailLog = (new EmailLog())
            ->where($where)
            ->order('send_time', 'desc')
            ->findOrEmpty();

        // 没有验证码 或 最新验证码已校验 或 已过期(有效期：5分钟)
        if ($emailLog->isEmpty() || $emailLog->is_verify || ($emailLog->send_time < time() - 5 * 61) ) {
            return false;
        }

        // 更新校验状态
        if($emailLog->code == $code) {
            $emailLog->check_num = $emailLog->check_num + 1;
            $emailLog->is_verify = YesNoEnum::YES;
            $emailLog->save();
            return true;
        }

        // 更新验证次数
        $emailLog->check_num = $emailLog->check_num + 1;
        $emailLog->save();
        return false;
    }
}