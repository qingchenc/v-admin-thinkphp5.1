<?php

namespace QingChen\Admin\validate;

use think\Validate;

class AdminBannerValidate extends Validate
{
    protected $rule =   [
        'attachment_id' => 'require',
        'position'      => 'require',
        'is_link'       => 'require',
        'sort'          => 'require',
    ];

    protected $message  =   [
        'attachment_id.require' => '图片文件不可为空',
        'position.require'      => '位置不可为空',
        'is_link.require'       => '是否跳转不可为空',
        'sort.require'          => '排序不可为空',
    ];
}
