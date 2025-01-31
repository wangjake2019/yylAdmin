<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 接口速率缓存
namespace app\common\cache\admin;

use think\facade\Cache;

class ApiRateCache
{
    /**
     * 缓存key
     *
     * @param integer $admin_user_id 用户id
     * @param string  $menu_url      菜单url
     * 
     * @return string
     */
    public static function key($admin_user_id, $menu_url)
    {
        $key = 'admin_apirate:' . $admin_user_id . ':' . $menu_url;

        return $key;
    }

    /**
     * 缓存设置
     *
     * @param integer $admin_user_id 用户id
     * @param string  $menu_url      菜单url
     * @param integer $ttl           有效时间（秒）
     * 
     * @return bool
     */
    public static function set($admin_user_id, $menu_url, $ttl = 10)
    {
        $key = self::key($admin_user_id, $menu_url);
        $val = 1;

        $res = Cache::set($key, $val, $ttl);

        return $res;
    }

    /**
     * 缓存获取
     *
     * @param integer $admin_user_id 用户id
     * @param string  $menu_url      菜单url
     * 
     * @return string
     */
    public static function get($admin_user_id, $menu_url)
    {
        $key = self::key($admin_user_id, $menu_url);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param integer $admin_user_id 用户id
     * @param string  $menu_url      菜单url
     * 
     * @return bool
     */
    public static function del($admin_user_id, $menu_url)
    {
        $key = self::key($admin_user_id, $menu_url);
        $res = Cache::delete($key);

        return $res;
    }

    /**
     * 缓存自增
     *
     * @param integer $admin_user_id 用户id
     * @param string  $menu_url      菜单url
     * 
     * @return bool
     */
    public static function inc($admin_user_id, $menu_url)
    {
        $key = self::key($admin_user_id, $menu_url);
        $res = Cache::inc($key);

        return $res;
    }
}
