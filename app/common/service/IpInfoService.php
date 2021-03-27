<?php
/*
 * @Description  : IP信息
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-07-14
 * @LastEditTime : 2021-03-26
 */

namespace app\common\service;

use think\facade\Request;
use app\common\cache\IpInfoCache;

class IpInfoService
{
    /**
     * 获取IP信息
     *
     * @param string $ip IP地址
     *
     * @return array
     */
    public static function info($ip = '')
    {
        if (empty($ip)) {
            $ip = Request::ip();
        }

        $ip_info = IpInfoCache::get($ip);

        if (empty($ip_info)) {
            $url = 'http://ip.taobao.com/outGetIpInfo?ip=' . $ip . '&accessKey=alibaba-inc';
            $par = [
                'ip' => $ip,
                'accessKey' => 'alibaba-inc'
            ];
            $res = http_post($url, $par);

            $ip_info = [
                'ip'       => $ip,
                'country'  => '',
                'province' => '',
                'city'     => '',
                'area'     => '',
                'region'   => '',
                'isp'      => '',
            ];

            if ($res) {
                if ($res['code'] == 0 && $res['data']) {
                    $data = $res['data'];

                    $country  = $data['country'];
                    $province = $data['region'];
                    $city     = $data['city'];
                    $area     = $data['area'];
                    $region   = $country . $province . $city . $area;
                    $isp      = $data['isp'];

                    $ip_info['ip']       = $ip;
                    $ip_info['country']  = $country;
                    $ip_info['province'] = $province;
                    $ip_info['city']     = $city;
                    $ip_info['region']   = $region;
                    $ip_info['area']     = $area;
                    $ip_info['isp']      = $isp;

                    IpInfoCache::set($ip, $ip_info);
                }
            }
        }

        return $ip_info;
    }
}
