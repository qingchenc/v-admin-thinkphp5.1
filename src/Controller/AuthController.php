<?php

namespace QingChen\Admin\Controller;

use think\facade\Request;
use think\response\View;
use think\facade\Session;
use think\Exception;
use QingChen\Admin\Controller\AdminController;
use QingChen\Admin\validate\AdminLoginValidate;

class AuthController extends AdminController
{
    /**
     * 输出登录页面
     *
     * @return View
     */
    public function getLogin()
    {
        $loginViewPath = config('admin.view_path').'/views/login.html';
        $adminName     = config('admin.admin_name');
        $adminBackgroundImage     = config('admin.admin_background_image');
        $adminBackgroundImageShow = config('admin.admin_background_image_show');

        return view($loginViewPath,[
            'adminName'                => $adminName,
            'adminBackgroundImage'     => $adminBackgroundImage,
            'adminBackgroundImageShow' => $adminBackgroundImageShow
        ]);
    }

    public function postLogin(Request $request){
        try{
            $requestData = $request::param();
            $validate    = new AdminLoginValidate;
            if(!$validate->check($requestData)){
                return $this->responseJson(['message' => $validate->getError(),'data' => []],202);
            }else{
                $model = config('admin.database.users_model');
                $adminUserData = $model::where(['username' => $requestData['username']])->find();
                if(!$adminUserData){
                    return $this->responseJson(['message' => '用户名不存在','data' => []],202);
                }
                if($adminUserData['password'] != md5($requestData['password'])){
                    return $this->responseJson(['message' => '密码不正确','data' => []],202);
                }
                if($adminUserData['status'] == 2){
                    return $this->responseJson(['message' => '该用户已禁止登录','data' => []],202);
                }
                Session::set('adminUserId',$adminUserData['id']);
                return $this->responseJson(['message' => '登录成功','data' => [
                    'adminUserId' => $adminUserData['id']
                ]],200);
            }
        }catch (Exception $e){
            return $this->responseJson(['message' => $e->getLine().'|'.$e->getFile().'|'.$e->getMessage(),'data' => []],202);
        }
    }

    public function getLogout(){
        try{
            $result = Session::pull('adminUserId');
            if($result){
                return $this->responseJson(['message' => '退出成功','data' => []],200);
            }else{
                return $this->responseJson(['message' => '退出失败','data' => []],202);
            }
        }catch (Exception $e){
            return $this->responseJson(['message' => $e->getLine().'|'.$e->getFile().'|'.$e->getMessage(),'data' => []],202);
        }
    }
}
