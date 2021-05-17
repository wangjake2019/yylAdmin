<?php
/*
 * @Description  : 会员日志中间件
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-11-24
 * @LastEditTime : 2021-05-17
 */

namespace app\index\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;
use app\common\service\ApiService;
use app\common\service\MemberLogService;

class MemberLogMiddleware
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
        $response = $next($request);

        $api_url   = request_pathinfo();
        $whitelist = ApiService::whiteList();

        if (!in_array($api_url, $whitelist)) {
            $is_log = Config::get('index.is_log', false);

            if ($is_log) {
                $member_id = member_id();

                if ($member_id) {
                    $response_data = $response->getData();
                    
                    if (isset($response_data['code'])) {
                        $member_log['response_code'] = $response_data['code'];
                    }
                    if (isset($response_data['msg'])) {
                        $member_log['response_msg'] = $response_data['msg'];
                    } else {
                        if (isset($response_data['message'])) {
                            $member_log['response_msg'] = $response_data['message'];
                        }
                    }

                    $member_log['member_id'] = $member_id;
                    MemberLogService::add($member_log);
                }
            }
        }

        return $response;
    }
}
