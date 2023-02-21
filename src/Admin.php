<?php

namespace QingChen\Admin;

use QingChen\Admin\Auth\AuthPermission;

class Admin{

    /**
     * The v-admin version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public function url($url)
    {
        $prefix = (string) config('admin.prefix');

        return "/$prefix/".trim($url, '/');
    }

    public function replaceField($field){
        if (strpos($field, '.') !== false) {
            list($relationName, $relationColumn) = explode('.', $field);
            $field = $relationName.ucfirst($relationColumn);
        }
        return $field;
    }

    /**
     * 判断是否是登录状态
     *
     * @return boolean
     */
    public function isLogin(){
        return (bool)AuthPermission::getAdminUserId();
    }

    public function requireFile($file){
        return require $file;
    }

    public function adminPath($path = ''){
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    // public function user(){
    //     return AdminUser::getAdminUserInfo();
    // }
    //
    // public function userId(){
    //     return AdminUser::getAdminUserId();
    // }
    //
    // public function permission($request){
    //     return AdminUser::permission($request);
    // }
    //
    // public function isAdministrator(){
    //     return AdminUser::isAdministrator();
    // }
    //
    // public function getAdminUserMenuList(){
    //     return AdminUser::getAdminUserMenuList();
    // }

}