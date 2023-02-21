<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminUsersValidate extends Validate
{
    protected $rule =   [
        'username'  => 'require|min:1',
        'password'  => 'require',
        'role'      => 'require',
    ];

    protected $message  =   [
        'username.require' => '用户名不可为空',
        'username.min'     => '用户名称长度太短',
        'password.require' => '密码不可为空',
        'role.require'     => '用户角色不可为空',
    ];

//    protected $scene = [
//        'username_password' => ['username','password'],
//    ];
}