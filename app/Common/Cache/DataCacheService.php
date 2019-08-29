<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/6/1
 * Time: 14:20
 */

namespace App\Common\Cache;


class DataCacheService
{
    protected static $cacheData = [];

    public static function get($key='', $default='')
    {
        return self::$cacheData[$key] ?? $default;
    }

    public static function set($key='', $value='')
    {
        self::$cacheData[$key] = $value;
    }
}
