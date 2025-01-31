<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 留言管理缓存
namespace app\common\cache\cms;

use think\facade\Cache;

class CommentCache
{
    /**
     * 缓存键名
     *
     * @param string $comment_id 留言id
     * 
     * @return string
     */
    public static function key($comment_id = '')
    {
        $key = 'cms_comment:' . $comment_id;

        return $key;
    }

    /**
     * 缓存写入
     *
     * @param string  $comment_id 留言id
     * @param mixed   $comment    留言信息
     * @param integer $ttl        有效时间（秒）
     * 
     * @return bool
     */
    public static function set($comment_id = '', $comment, $ttl = 0)
    {
        $key = self::key($comment_id);
        $val = $comment;
        if (empty($ttl)) {
            $ttl = 1 * 24 * 60 * 60 + mt_rand(0, 99);
        }

        $res = Cache::set($key, $val, $ttl);

        return $res;
    }

    /**
     * 缓存读取
     *
     * @param string $comment_id 留言id
     * 
     * @return mixed
     */
    public static function get($comment_id = '')
    {
        $key = self::key($comment_id);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param string $comment_id 留言id
     * 
     * @return bool
     */
    public static function del($comment_id = '')
    {
        $key = self::key($comment_id);
        $res = Cache::delete($key);

        return $res;
    }

    /**
     * 缓存自增
     *
     * @param string  $comment_id 留言id
     * @param integer $step       步长
     *
     * @return bool
     */
    public static function inc($comment_id = '', $step = 1)
    {
        $key = self::key($comment_id);
        $res = Cache::inc($key, $step);

        return $res;
    }
}
