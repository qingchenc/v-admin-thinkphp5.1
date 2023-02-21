<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminLoginValidate extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require|min:1',
    ];

    protected $message = [
        'username.require'  => '用户名不可为空',
        'password.min'      => '密码长度太短',
        'password.require'  => '密码不可为空',
    ];
}