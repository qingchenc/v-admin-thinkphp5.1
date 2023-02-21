<?php

namespace QingChen\Admin\Providers;

use think\facade\Request;

class AdminServiceProvider
{
    /**
     * 应用程序的路由中间件
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth' => \QingChen\Admin\Middleware\Authenticate::class,
    ];

    /**
     * 应用程序的钩子行为
     *
     * @var array
     */
    // protected $routeBehavior = [
    //     'admin.init' => \extend\Encore\Src\Behavior\Init::class,
    // ];

    /**
     * 启动服务提供商
     *
     * @return void
     */
    public function boot()
    {
        // $this->publishes([__DIR__.'/../../config/admin.php' => config_path('admin.php')], 'laravel-admin');
        // $this->publishes([__DIR__.'/../../assets' => public_path('packages/admin')], 'laravel-admin');
        //
        // if (file_exists($routes = admin_path('routes.php'))) {
        //     require $routes;
        //     $this->app['admin.router']->register();
        // }
    }

    /**
     * 获取路由中间件
     *
     * @return mixed|string
     */
    public function getAuthRouteMiddleware(){
        return $this->routeMiddleware['admin.auth'];
    }

    // public function getBehaviorInit(){
    //     return $this->routeBehavior['admin.init'];
    // }
}
