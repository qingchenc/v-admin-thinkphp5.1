<?php

namespace QingChen\Admin\Support\Http;

class Client{
    /**
     * 请求发送的域名
     *
     * @var
     */
    private $url;

    /**
     * 设置超时时间
     *
     * @var int
     */
    private $timeout = 10;

    /**
     * 是否是https请求
     *
     * @var bool
     */
    private $https = false;

    /**
     * 对https的验证
     *
     * @var bool
     */
    private $verify = false;

    /**
     * 请求头的信息
     *
     * @var array
     */
    private $headers = [];

    /**
     * 请求体的信息
     *
     * @var
     */
    private $formParams;

    /**
     * cookie
     *
     * @var
     */
    private $cookie;

    /**
     * construct function
     *
     * @param array $dataParams
     */
    public function __construct(array $dataParams = [])
    {
        $this->init($dataParams);
    }

    /**
     * 初始化函数
     *
     * @param $dataParams
     * @return void
     */
    protected function init($dataParams)
    {
        if(isset($dataParams['url'])){
            $this->url     = $dataParams['url'];
        }
        if($this->isStripos($dataParams['url'], "https://")){
            $this->https = true;
        }
        if(isset($dataParams['timeout'])){
            $this->timeout = $dataParams['timeout'];
        }

        if(isset($dataParams['verify'])){
            $this->verify = $dataParams['verify'];
        }
        if(isset($dataParams['headers'])){
            $this->headers = $this->handleHeaders($dataParams['headers']);
        }
        if(isset($dataParams['cookie'])){
            $this->cookie = $dataParams['cookie'];
        }
    }

    /**
     * 集中处理http请求
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array
     */
    public function request(string $method,$url = '', array $options = [])
    {
        //判断链接地址的完整性
        $https = $this->isStripos($url, "https://");
        $http  = $this->isStripos($url, "http://");
        $this->url = $this->url.$url;
        if($https || $http){
            $this->url   = $url;
            $this->https = $https;
        }

        if(isset($options['headers'])){
            $this->headers = $this->handleHeaders($options['headers']);
        }
        if(isset($options['form_params'])){
            $this->formParams = $options['form_params'];
        }
        if(isset($options['cookie'])){
            $this->cookie = $options['cookie'];
        }

        if(strtolower($method) == "post"){
            $responseData = $this->post();
        }else{
            $responseData = $this->get();
        }

        return $responseData;
    }

    /**
     * 发送post请求
     *
     * @return array
     */
    protected function post()
    {
        // 启动一个CURL会话
        $curl = curl_init();
        // 要访问的地址
        curl_setopt($curl, CURLOPT_URL, $this->url);
        // 对https的特殊处理
        if ($this->https || $this->verify) {
            // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            // 使用的SSL版本(2 或 3)
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        }
        // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // 使用自动跳转
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        // 自动设置Referer
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POST, true);

        if (!empty($this->headers)) {
            // 一个用来设置HTTP请求头字段的数组
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }
        if ($this->cookie) {
            // 设置cookie
            curl_setopt($curl, CURLOPT_COOKIE, $this->cookie);
        }

        // Post提交的数据包
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->formParams);
        // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 执行操作
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // 捕抓异常
            return ['code' => 500, 'data' => [], 'message' => 'Errno: '.curl_error($curl)];
        }

        // 关闭CURL会话
        curl_close($curl);

        return ['code' => 200, 'data' => $this->jsonToArray($response), 'message' => ''];
    }

    /**
     * 发送get请求
     *
     * @return array
     */
    protected function get()
    {
        $queryParams = '';
        if(!empty($this->formParams)){
            $queryParams = http_build_query($this->formParams);
        }

        // 启动一个CURL会话
        $curl = curl_init();
        // 要访问的地址
        curl_setopt($curl, CURLOPT_URL, $queryParams ? $this->url.'?'.$queryParams : $this->url);

        if ($this->https || $this->verify) {
            // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            // 使用的SSL版本(2 或 3)
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        }
        if (!empty($this->headers)) {
            // 一个用来设置HTTP请求头字段的数组
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }
        if ($this->cookie) {
            // 设置cookie
            curl_setopt($curl, CURLOPT_COOKIE, $this->cookie);
        }

        // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // 执行操作
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // 捕抓异常
            return ['code' => 500, 'data' => [], 'message' => 'Errno: '.curl_error($curl)];
        }

        // 关闭CURL会话
        curl_close($curl);

        return ['code' => 200, 'data' => $this->jsonToArray($response), 'message' => ''];
    }

    /**
     * 将请求有headers信息进行格式化
     *
     * @param array $headers
     * @return array
     */
    protected function handleHeaders(array $headers = []){
        $headersData = [];

        if(count($headers) > 0){
            foreach ($headers as $key => $val){
                $headersData[] = $key . ': ' . $val;
            }
        }

        return $headersData;
    }

    /**
     * 判断字符串中是否含有另外一个字符串
     *
     * @param $url
     * @param $str
     * @return bool
     *         true: 说明含有
     *         false: 说明没有
     */
    protected function isStripos($url,$str){
        return stripos($url, $str) !== false;
    }

    /**
     * json转化Array
     *
     * @param $json
     * @return mixed
     */
    protected function jsonToArray($json){
        return json_decode($json,TRUE);
    }
}

