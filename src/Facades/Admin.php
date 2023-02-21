<?php

namespace QingChen\Admin\Facades;

use think\Facade;

/**
 * @method string url(string $url = '') static 获取当前完整URL
 * @method string replaceField(string $field = '') static 处理字符串
 * @method bool isLogin() static 判断当前登录状态
 * @method string requireFile(string $file = '') static 加载引入的文件
 *
 * @see \QingChen\Admin\Admin
 */
class Admin extends Facade
{
    protected static function getFacadeClass()
    {
        return \QingChen\Admin\Admin::class;
    }
}
