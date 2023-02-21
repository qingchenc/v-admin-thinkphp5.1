<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminDictsValidate extends Validate
{
    protected $rule =   [
        'title'     => 'require',
        'slug'      => 'require',
        'sort'      => 'require',
        'status'    => 'require',
    ];

    protected $message  =   [
        'title.require'     => '标题不可为空',
        'slug.require'      => '标识不可为空',
        'sort.require'      => '排序不可为空',
        'status.require'    => '状态不可为空',
    ];

    protected $scene = [
        'edit'  =>  ['title','sort','status'],
    ];

}
