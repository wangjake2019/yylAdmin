<?php
/*
 * @Description  : 接口文档
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-17
 * @LastEditTime : 2021-05-06
 */

namespace app\common\service;

use app\common\service\AdminUserService;

class AdminApidocService
{
    /**
     * 接口文档
     *
     * @return array
     */
    public static function apidoc()
    {
        $admin_user      = AdminUserService::info(admin_user_id());
        $admin_token     = $admin_user['admin_token'];
        $admin_token_sub = substr($admin_token, 0, 16) . '...';

        $data['apidoc_url']      = server_url() . '/apidoc/index.html?t=' . time();
        $data['apidoc_pwd']      = config('apidoc.auth.password');
        $data['admin_user_id']   = $admin_user['admin_user_id'];
        $data['admin_token']     = $admin_token;
        $data['admin_token_sub'] = $admin_token_sub;

        return $data;
    }
}
