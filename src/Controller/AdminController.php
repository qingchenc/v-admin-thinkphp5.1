<?php

namespace QingChen\Admin\Controller;

use think\Controller;
use think\facade\View;
use think\facade\Response;
use think\facade\Request;
use QingChen\Admin\Layout\Content;

class AdminController extends Controller
{
    /**
     * 当前的内容资源的标题
     *
     * @var string
     */
    protected $title = 'Title';

    /**
     * 设置描述信息
     *
     * @var array
     */
    protected $description = [];

    /**
     * 获取当前内容资源的标题
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * 判断当前的请求类型
     *
     * @return array
     */
    public function isGetData()
    {
        $requestGetData = Request::param();

        if(isset($requestGetData['page']) && isset($requestGetData['limit'])){
            return ['get_data' => true,'page' => $requestGetData['page'],'limit' => $requestGetData['limit']];
        }

        return ['get_data' => false,'page' => '','limit' => ''];
    }

    /**
     * 输出grid表格页面
     *
     * @param Content $content
     * @return string|\think\response
     * @throws \Exception
     */
    public function index(Content $content)
    {
        $isGetData       = $this->isGetData();
        $requestUrl      = Request::url();
        $requestUrlAllow = ['admin','/admin','/admin/','admin/index','/admin/index','/admin/index/'];
        $urlKey          = true;
        foreach ($requestUrlAllow as $key => $val){
            if($requestUrl == $val){
                $urlKey = false;
            }
        }

        $content = $content->title($this->title())->description();
        if(!$urlKey){
            $content = $content->render();
        }else{
            $content = $content->body($this->grid())->renderGrid();
        }

        if($isGetData['get_data']){
            $result = $this->grid()->build();

            return $this->responseJson(['message' => "success",'count' => $result->total(),'current_page' => $result->currentPage(),'last_page' => $result->lastPage(),'limit' => intval($isGetData['limit']),'data' => $result->getCollection()],200);
        }else{
            return $content;
        }
    }

    /**
     * 输出后台管理的首页
     *
     * @return string
     */
    public function home()
    {
        $viewPath = config('admin.view_path').'/views';

        // 其他方法输出视图
        // view($viewPath.'/home.html');
        // $this->fetch();
        return View::fetch($viewPath.'/home.html');
    }

    /**
     * 封装接口返回的数据格式类型
     *
     * @param $data
     * @param $code
     * @return \think\response
     */
    public function responseJson($data,$code){
        $data['code'] = $code;

        return Response::create($data, 'json')->code($code);
    }
}
