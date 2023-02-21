<?php

namespace QingChen\Admin\Behavior;

use think\facade\Request;
use QingChen\Admin\Routing\Route;

class Init{

    public function run(Request $request, $params)
    {
        $routeFile = 'route.php';

        (new Route())->setAdminRoutes();

        require app()->getAppPath().config('admin.directory').'\\'.$routeFile;
    }
}
