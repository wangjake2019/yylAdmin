<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 权限验证中间件
namespace app\admin\middleware\admin;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;
use app\common\cache\admin\UserCache;
use app\common\service\admin\MenuService;

class RuleVerifyMiddleware
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $menu_url = menu_url();

        // 菜单是否存在
        if (!menu_is_exist($menu_url)) {
            $msg   = '接口地址错误';
            $debug = Config::get('app.app_debug');
            if ($debug) {
                $msg .= '：' . $menu_url;
            }
            exception($msg, 404);
        }

        // 菜单是否无需权限
        if (!menu_is_unauth($menu_url)) {
            $admin_user_id = admin_user_id();
            
            // 用户是否超管
            if (!admin_is_super($admin_user_id)) {
                $admin_user = UserCache::get($admin_user_id);

                if (empty($admin_user)) {
                    exception('登录已失效，请重新登录', 401);
                }

                if ($admin_user['is_disable'] == 1) {
                    exception('账号已禁用，请联系管理员', 401);
                }

                if (!in_array($menu_url, $admin_user['roles'])) {
                    $admin_menu = MenuService::info($menu_url);
                    exception('你没有权限操作：' . $admin_menu['menu_name'], 403);
                }
            }
        }

        return $next($request);
    }
}
