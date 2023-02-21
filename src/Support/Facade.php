<?php
// +----------------------------------------------------------------------
// | 封装门面类
// +----------------------------------------------------------------------
// | Date: 2021-12-27
// +----------------------------------------------------------------------
// | Author: 清晨 <1849003043@qq.com>
// +----------------------------------------------------------------------

namespace QingChen\Admin\Support;

use RuntimeException;

abstract class Facade
{

    /**
     * 已解析的对象实例
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * 获取门面解析的根对象
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * 获取组件的注册名称
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * 从容器中解析facade根实例
     *
     * @param  object|string  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }else{
            return static::$resolvedInstance[$name] = new $name;
        }
    }

    /**
     * 清除某一个门面类
     *
     * @param  string  $name
     * @return void
     */
    public static function clearResolvedInstance($name)
    {
        unset(static::$resolvedInstance[$name]);
    }

    /**
     * 清空门面类
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * 进行静态处理
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();

        if(!$instance){
            throw new RuntimeException('A facade root has not been set.');
        }

        //可变数量的参数列表
        return $instance->$method(...$arguments);
    }
}
