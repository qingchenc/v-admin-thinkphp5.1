<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminMenuValidate extends Validate
{
    protected $rule =   [
        'parent_id' => 'require',
        'title'     => 'require|min:1',
        'roles'     => 'require',
    ];

    protected $message  =   [
        'parent_id.require' => '父级不可为空',
        'name.require'      => '菜单名不可为空',
        'name.min'          => '菜单名称长度太短',
        'roles.require'     => '关联角色不可为空',
    ];

}