<?php

namespace QingChen\Admin\Behavior;

use think\facade\Request;
use QingChen\Admin\Support\Str;

class AdminModuleInit{

    public function run(Request $request, $params)
    {
        // $moduleInit = $request::module();
        // $moduleName = config('admin.module_name');
        // if($moduleInit == $moduleName){
        //     config('template.view_path');
        // }

        $requestUrl = $request::url();
        if(Str::is('/admin/auth/login',$requestUrl)){
            config('template.view_path',config('admin.view_path'));
        }
    }
}