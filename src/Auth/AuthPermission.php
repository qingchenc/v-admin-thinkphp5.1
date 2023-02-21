<?php

namespace QingChen\Admin\Auth;

use think\facade\Session;

use QingChen\Admin\Support\Str;
use QingChen\Admin\Support\Tree;
use QingChen\Admin\Auth\Database\AdminUsers;
use QingChen\Admin\Auth\Database\AdminMenus;
use QingChen\Admin\Auth\Database\AdminPermissions;
use QingChen\Admin\Auth\Database\AdminRoleMenus;
use QingChen\Admin\Auth\Database\AdminRolePermissions;

class AuthPermission{

    /**
     * 防止实例化该类
     *
     * AdminUser constructor.
     */
    private function __construct(){}

    /**
     * 获取后台管理人员id
     *
     * @return array|mixed|null
     */
    public static function getAdminUserId(){
        return Session::get('adminUserId');
    }

    /**
     * 获取后台管理人员详情信息
     *
     * @return AdminUsers|null
     * @throws \think\exception\DbException
     */
    public static function getAdminUserInfo(){
        $adminUserId    = self::getAdminUserId();
        $adminUserModel = new AdminUsers();
        $adminUserInfo  = $adminUserModel->getFindData($adminUserId);
        return $adminUserInfo;
    }

    /**
     * 判断当前用户是否为超级管理员
     *
     * @return bool
     * @throws \think\exception\DbException
     */
    public static function isAdministrator(){
        $adminUserRolesData = self::getUserRolesData();
        $isAdministrator    = false;
        foreach ($adminUserRolesData as $key => $val){
           if($val['slug'] == 'administrator'){$isAdministrator = true;break;}
        }
        return $isAdministrator;
    }

    /**
     * 获取用户的角色
     *
     * @return array|bool|float|int|mixed|object|\stdClass|null
     * @throws \think\exception\DbException
     */
    public static function getUserRolesData(){
        $adminUserId        = self::getAdminUserId();
        if(!$adminUserId){ return false; }
        $adminUserFirstData = AdminUsers::get($adminUserId);
        $adminUserRolesData = $adminUserFirstData->roles;
        return $adminUserRolesData;
    }

    /**
     * 获取后台人员的菜单列表
     *
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getAdminUserMenuList(){
        $adminUserRolesData = self::getUserRolesData();
        $isAdministrator    = false;
        $adminRolesIdData   = [];
        foreach ($adminUserRolesData as $key => $val){
            array_push($adminRolesIdData,$val['id']);
            if($val['slug'] == 'administrator'){
                $isAdministrator = true;
            }
        }
        if($isAdministrator){
            //如果是超级管理员，直接输出所有菜单管理
            $adminMenusData = AdminMenus::all(function($query){
                $query->where(['status' => 1])->order('sort','asc');
            });
        }else{
            //获取该角色下的菜单id
            $adminMenusIdData = AdminRoleMenus::all(function($query) use ($adminRolesIdData){
                $query->whereIn('role_id', $adminRolesIdData)->where(['is_use' => 1]);
            });
            //菜单id去重
            $adminMenusId = [];
            foreach ($adminMenusIdData as $key => $val){
                if(!in_array($val['menu_id'],$adminMenusId)){
                    array_push($adminMenusId,$val['menu_id']);
                }
            }
            //菜单数据
            $adminMenusData = AdminMenus::all(function($query) use ($adminMenusId){
                $query->whereIn('id', $adminMenusId)->where(['status' => 1])->order('sort','asc');
            });
        }
        //转换树结构
        $adminMenusData = Tree::generateTree(collection($adminMenusData)->toArray());
        return $adminMenusData;
    }

    /**
     * 判断权限问题
     *
     * @param $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function permission($request){
        $adminUserId = self::getAdminUserId();
        if(!$adminUserId){
            return ['code' => 4002,'message' => '请重新登录','data' => []];
        }
        return AuthPermission::checkRoutePermission($request,$adminUserId);
    }

    /**
     * 检测路由权限
     *
     * @param $request
     * @param $adminUserId
     * @return array
     * @throws \think\exception\DbException
     */
    public static function checkRoutePermission($request,$adminUserId){
        //获取用户的角色
        $adminUserRolesData = self::getUserRolesData();
        $isAdministrator    = false;
        $adminRolesIdData   = [];
        foreach ($adminUserRolesData as $key => $val){
            array_push($adminRolesIdData,$val['id']);
            if($val['slug'] == 'administrator'){
                $isAdministrator = true;
            }
        }

        //判断是否是超级管理员
        if($isAdministrator){
            return ['code' => 200,'message' => '权限正常','data' => []];
        }

        //获取该角色下的权限id
        $adminPermissionsIdData = AdminRolePermissions::all(function($query) use ($adminRolesIdData){
            $query->whereIn('role_id', $adminRolesIdData)->where(['is_use' => 1]);
        });

        //权限id去重
        $adminPermissionsId = [];
        foreach ($adminPermissionsIdData as $key => $val){
            if(!in_array($val['permission_id'],$adminPermissionsId)){
                array_push($adminPermissionsId,$val['permission_id']);
            }
        }

        //权限数据
        $adminPermissionsData = AdminPermissions::all(function($query) use ($adminPermissionsId){
            $query->whereIn('id', $adminPermissionsId)->where(['status' => 1]);
        });

        //检测权限
        $permissionsKey = false;
        foreach ($adminPermissionsData as $key => $val){
            $method    = explode(',',$val['http_method']);
            $http_path = explode("\n", Str::replaceRnAttribute($val['http_path']));
            $matches   = array_map(function ($path) use ($method) {
                if (Str::contains($path, ':')) {
                    list($method, $path) = explode(':', $path);
                    $method = explode(',', $method);
                }
                return compact('method', 'path');
            }, $http_path);

            //验证请求方法
            $http_method_key = Str::contains($request->method(),$matches[0]['method'][0] ? $matches[0]['method'] : '');

            //验证请求路径
            $http_path_key   = Str::is($http_path,$request->baseUrl());

            if($http_method_key && $http_path_key){$permissionsKey = true;break;}
        }

        if(!$permissionsKey){
            return ['code' => 4001,'message' => '暂无权限查看','data' => []];
        }

        return ['code' => 200,'message' => '权限正常','data' => []];
    }
}
