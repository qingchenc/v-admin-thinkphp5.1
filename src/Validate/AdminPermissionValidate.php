<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminPermissionValidate extends Validate
{
    protected $rule =   [
        'name'  => 'require|min:1',
        'slug'  => 'require',
    ];

    protected $message  =   [
        'name.require' => '角色名不可为空',
        'name.min'     => '角色名称长度太短',
        'slug.require' => '标识不可为空',
    ];

//    protected $scene = [
//        'username_password' => ['username','password'],
//    ];
}