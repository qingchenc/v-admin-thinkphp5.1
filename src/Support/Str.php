<?php
// +----------------------------------------------------------------------
// | 封装字符串功能方法
// +----------------------------------------------------------------------
//   Date: 2021-07-19
// +----------------------------------------------------------------------
// | Author: 清晨 <1849003043@qq.com>
// +----------------------------------------------------------------------

namespace QingChen\Admin\Support;

class Str
{

    /**
     * 将带有\r\n的字符串中的\r替换掉（一般是处理textarea的值）
     *
     * @param string $value
     *
     * @return mixed
     */
    public static function replaceRnAttribute($value)
    {
        return str_replace("\r\n", "\n", $value);
    }

    /**
     * 确定给定的字符串是否与给定的模式匹配
     *
     * @param $pattern
     * @param $value
     * @return bool
     */
    public static function is($pattern, $value)
    {
        $patterns = is_array($pattern) ? $pattern : (array) $pattern;

        if (empty($patterns)) {return false;}

        foreach ($patterns as $pattern) {
            if ($pattern == $value) {return true;}

            if (mb_strpos($pattern,$value) !== false){return true;}

            $pattern = preg_quote($pattern, '#');
            $pattern = str_replace('\*', '.*', $pattern);

            if (preg_match('#^'.$pattern.'\z#u', $value) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * 确定给定的字符串是否包含给定的子字符串
     *
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function contains($haystack,$needles)
    {
        if(empty($needles)){return true;}
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

}
