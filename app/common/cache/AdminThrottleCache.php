<?php
/*
 * @Description  : 请求频率缓存
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-06-12
 * @LastEditTime : 2021-03-23
 */

namespace app\common\cache;

use think\facade\Cache;

class AdminThrottleCache
{
    /**
     * 缓存key
     *
     * @param integer $admin_admin_id 管理员id
     * @param string  $menu_url       菜单url
     * 
     * @return string
     */
    public static function key($admin_admin_id, $menu_url)
    {
        $key = 'AdminThrottle:' . $admin_admin_id . ':' . $menu_url;

        return $key;
    }

    /**
     * 缓存设置
     *
     * @param integer $admin_admin_id 管理员id
     * @param string  $menu_url       菜单url
     * @param integer $expire         有效时间（秒）
     * 
     * @return bool
     */
    public static function set($admin_admin_id, $menu_url, $expire = 10)
    {
        $key = self::key($admin_admin_id, $menu_url);
        $val = 1;
        $exp = $expire;

        $res = Cache::set($key, $val, $exp);

        return $res;
    }

    /**
     * 缓存获取
     *
     * @param integer $admin_admin_id 管理员id
     * @param string  $menu_url       菜单url
     * 
     * @return string
     */
    public static function get($admin_admin_id, $menu_url)
    {
        $key = self::key($admin_admin_id, $menu_url);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param integer $admin_admin_id 管理员id
     * @param string  $menu_url       菜单url
     * 
     * @return bool
     */
    public static function del($admin_admin_id, $menu_url)
    {
        $key = self::key($admin_admin_id, $menu_url);
        $res = Cache::delete($key);

        return $res;
    }

    /**
     * 缓存自增
     *
     * @param integer $admin_admin_id 管理员id
     * @param string  $menu_url       菜单url
     * 
     * @return bool
     */
    public static function inc($admin_admin_id, $menu_url)
    {
        $key = self::key($admin_admin_id, $menu_url);
        $res = Cache::inc($key);

        return $res;
    }
}
