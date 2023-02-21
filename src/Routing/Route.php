<?php

namespace QingChen\Admin\Routing;

use think\facade\Route as ThinkRouter;

class Route
{
    /**
     * Create a new admin router instance
     */
    public function __construct()
    {
        $this->setAdminRoutes();
    }

    /**
     * Set auth route.
     *
     * @return void
     */
    public function setAdminRoutes()
    {
        $prefix = config('admin.prefix');

        ThinkRouter::group($prefix,function(ThinkRouter $router) {
            // $router::resources([
            //     'auth/users'       => 'UserController',
            //     'auth/roles'       => 'RoleController',
            //     'auth/permissions' => 'PermissionController',
            //     'auth/menu'        => 'MenuController',
            // ]);

            $router::get('auth/login', 'QingChen\Admin\Controller\AuthController/getLogin');
            $router::post('auth/login', 'QingChen\Admin\Controller\AuthController/postLogin');
            $router::post('auth/logout', 'QingChen\Admin\Controller\AuthController/getLogout');
        })->middleware('admin.auth')->before(['QingChen\Admin\Behavior\AdminModuleInit']);
    }
}
