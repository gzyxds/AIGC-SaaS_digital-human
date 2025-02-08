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

declare (strict_types=1);

namespace app\common\http\middleware;

use app\common\model\tenant\Tenant;
use app\common\service\JsonService;
use Closure;
use think\facade\Config;

/**
 * 自定义跨域中间件
 * Class LikeAdminAllowMiddleware
 * @package app\common\http\middleware
 */
class LikeAdminAllowMiddleware
{
    /**
     * 允许的请求头常量
     */
    private const ALLOWED_HEADERS = [
        'Authorization', 'Sec-Fetch-Mode', 'DNT', 'X-Mx-ReqToken', 'Keep-Alive', 'User-Agent',
        'If-Match', 'If-None-Match', 'If-Unmodified-Since', 'X-Requested-With', 'If-Modified-Since',
        'Cache-Control', 'Content-Type', 'Accept-Language', 'Origin', 'Accept-Encoding', 'Access-Token',
        'token', 'version',
    ];

    /**
     * @notes 跨域处理
     * @param $request
     * @param Closure $next
     * @param array|null $header
     * @return mixed|\think\Response|\think\response\Json|\think\response\View
     * @author JXDN
     * @date 2024/09/11 14:11
     */
    public function handle($request, Closure $next, ?array $header = [])
    {
        // 设置跨域头
        $this->setCorsHeaders();

        // 如果是OPTIONS请求，直接返回响应
        if (strtoupper($request->method()) === 'OPTIONS') {
            return response();
        }

        // 安装检测
        $install = file_exists(root_path() . '/config/install.lock');
        if (!$install) {
            return JsonService::fail('程序未安装', [], -2);
        }

        // 获取租户信息
        $tenantModel = new Tenant();
        $domain = preg_replace('/^https?:\/\/|\/$/', '', $request->domain());
        $pathSegments = explode('/', $request->pathinfo());
        $firstSegment = $pathSegments[0];

        if (str_contains($firstSegment, 'crontab')) {
            return $next($request);
        }

        // 处理API请求
        if (str_contains($firstSegment, 'api')) {
            if ($firstSegment !== 'platformapi') {
                return $this->handleTenantAccess($tenantModel, $domain, $request, $next);
            }
        } else {
            // 处理页面请求
            if ($firstSegment !== 'platform') {
                return $this->handleTenantAccess($tenantModel, $domain, $request, $next, true);
            } else {
                if ($domain !== Config::get('project.http_host')) {
                    return view(app()->getRootPath() . 'public/error/platform/404.html');
                }
            }
        }

        return $next($request);
    }

    /**
     * 设置跨域头信息
     */
    private function setCorsHeaders()
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Headers'     => implode(', ', self::ALLOWED_HEADERS),
            'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE, post',
            'Access-Control-Max-Age'           => '1728000',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
    }

    /**
     * @notes 处理租户访问逻辑
     * @param Tenant $tenantModel
     * @param string $domain
     * @param $request
     * @param Closure $next
     * @param bool $isPage
     * @return mixed|\think\Response|\think\response\Json|\think\response\View
     * @author JXDN
     * @date 2024/09/11 14:06
     */
    private function handleTenantAccess(Tenant $tenantModel, string $domain, $request, Closure $next, bool $isPage = false)
    {
        // 通过别名访问租户
        $tenant = $tenantModel->where(['domain_alias' => $domain])->findOrEmpty();
        if (!$tenant->isEmpty() && $tenant->disable === 0 && $tenant->domain_alias_enable === 0) {
            $request->tenantId = $tenant->id;
            $request->tenantSn = $tenant->sn;
            return $next($request);
        } elseif (!$tenant->isEmpty()) {
            return $this->tenantDisabledResponse($isPage);
        }

        // 通过子域名访问租户
        $request->tenantSn = $request->subDomain();
        $tenant = $tenantModel->where(['sn' => $request->tenantSn])->findOrEmpty();
        if (!$tenant->isEmpty()) {
            if ($tenant->disable === 0) {
                $request->tenantId = $tenant->id;
                return $next($request);
            } else {
                return $this->tenantDisabledResponse($isPage);
            }
        }

        // 租户不存在或域名错误
        return $isPage ? view(app()->getRootPath() . 'public/error/tenant/404.html') : JsonService::fail('接口域名错误或租户不存在', [], 4, 0);
    }

    /**
     * @notes 返回租户停用的响应
     * @param bool $isPage
     * @return \think\response\Json|\think\response\View
     * @author JXDN
     * @date 2024/09/11 14:06
     */
    private function tenantDisabledResponse(bool $isPage)
    {
        return $isPage ? view(app()->getRootPath() . 'public/error/tenant/403.html') : JsonService::fail('该租户已停用', [], 3, 0);
    }
}
