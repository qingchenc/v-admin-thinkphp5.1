<?php

namespace QingChen\Admin\Middleware;

use think\Request;
use think\Middleware;

use QingChen\Admin\Support\Str;
use QingChen\Admin\Facades\Admin;

class Authenticate extends Middleware
{
    public function handle($request, \Closure $next)
    {
        if (!Admin::isLogin() && !$this->shouldPassThrough($request)) {
            return redirect(Admin::url('auth/login'))->params();
        }

        return $next($request);
    }

    /**
     * 确定请求是否具有应通过验证的URI
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = [
            Admin::url('auth/login'),
            Admin::url('auth/logout'),
        ];

        $requestUrl      = $request->url();
        $requestUrlAllow = ['admin','/admin','/admin/','admin/'];
        foreach ($requestUrlAllow as $key => $val) {
            if ($val == $requestUrl) {
                return false;
            }
        }

        foreach ($excepts as $key => $val) {
            if (Str::is($val, urldecode($request->url()))) {
                return true;
            }
        }

        return false;
    }
}
