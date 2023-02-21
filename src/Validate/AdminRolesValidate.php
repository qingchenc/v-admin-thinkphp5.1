<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminRolesValidate extends Validate
{
    protected $rule =   [
        'name'  => 'require|min:1',
        'slug'  => 'require',
        'permission' => 'require'
    ];

    protected $message  =   [
        'name.require' => '角色名不可为空',
        'name.min'     => '角色名称长度太短',
        'slug.require' => '标识不可为空',
        'permission.require' => '权限数据不可为空',
    ];

//    protected $scene = [
//        'username_password' => ['username','password'],
//    ];
}