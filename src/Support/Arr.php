<?php
// +----------------------------------------------------------------------
// | 封装数组功能方法
// +----------------------------------------------------------------------
// | Date: 2021-07-19
// +----------------------------------------------------------------------
// | Author: 清晨 <1849003043@qq.com>
// +----------------------------------------------------------------------

namespace QingChen\Admin\Support;

use ArrayAccess;
use InvalidArgumentException;

class Arr
{
    /**
     * 确定给定值是否可以访问数组
     *
     * @param $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * 往数组中添加新的键值
     *
     * @param $array
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function add($array,$key,$value)
    {
        if(is_null(static::get($array,$key))){
            static::set($array,$key,$value);
        }
        return $array;
    }

    /**
     * 返回数组中通过给定真值测试的第一个元素
     *
     * @param $array
     * @param callable|null $callback
     * @param null $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * 返回数组中通过给定真值测试的最后一个元素
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public static function last($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * 往数组中设置一个新的键值
     *
     * @param $array
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set($array,$key,$value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $array[$key] = $value;
        return $array;
    }

    /**
     * 获取数组中某个键的值
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        //判断是否是数据类型
        if(!static::accessible($array)){
            return value($default);
        }

        if(is_null($key)){
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if(strpos($key,'.') === false){
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * 该数组中是否存在这个键
     *
     * @param $array
     * @param $key
     * @return bool
     */
    public static function exists($array,$key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * 将多维数组展平成一维数组
     *
     * @param $array
     * @param $depth
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        $result = [];
        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1 ? array_values($item) : static::flatten($item, $depth - 1);
                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

}
