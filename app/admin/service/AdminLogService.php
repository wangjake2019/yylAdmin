<?php
/*
 * @Description  : 管理员日志
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-06
 * @LastEditTime : 2021-03-24
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\Request;
use app\common\utils\Datetime;
use app\common\cache\AdminLogCache;
use app\common\service\IpInfoService;

class AdminLogService
{
    /**
     * 管理员日志列表
     *
     * @param array   $where 条件
     * @param integer $page  分页
     * @param integer $limit 数量
     * @param array   $order 排序
     * @param string  $field 字段
     * 
     * @return array 
     */
    public static function list($where = [], $page = 1, $limit = 10, $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'admin_log_id,admin_admin_id,admin_menu_id,request_method,request_ip,request_region,request_isp,response_code,response_msg,create_time';
        }

        $where[] = ['is_delete', '=', 0];

        if (empty($order)) {
            $order = ['admin_log_id' => 'desc'];
        }

        $count = Db::name('admin_log')
            ->where($where)
            ->count('admin_log_id');

        $list = Db::name('admin_log')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['username'] = '';
            $list[$k]['nickname'] = '';
            $admin_admin = AdminAdminService::info($v['admin_admin_id']);
            if ($admin_admin) {
                $list[$k]['username'] = $admin_admin['username'];
                $list[$k]['nickname'] = $admin_admin['nickname'];
            }

            $list[$k]['menu_name'] = '';
            $list[$k]['menu_url']  = '';
            $admin_menu = AdminMenuService::info($v['admin_menu_id']);
            if ($admin_menu) {
                $list[$k]['menu_name'] = $admin_menu['menu_name'];
                $list[$k]['menu_url']  = $admin_menu['menu_url'];
            }
        }

        $pages = ceil($count / $limit);

        $data['count'] = $count;
        $data['pages'] = $pages;
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['list']  = $list;

        return $data;
    }

    /**
     * 管理员日志信息
     *
     * @param integer $admin_log_id 日志id
     * 
     * @return array
     */
    public static function info($admin_log_id)
    {
        $admin_log = AdminLogCache::get($admin_log_id);

        if (empty($admin_log)) {
            $admin_log = Db::name('admin_log')
                ->where('admin_log_id', $admin_log_id)
                ->find();

            if (empty($admin_log)) {
                exception('日志不存在：' . $admin_log_id);
            }

            if ($admin_log['request_param']) {
                $admin_log['request_param'] = unserialize($admin_log['request_param']);
            }

            $admin_log['username'] = '';
            $admin_log['nickname'] = '';
            $admin_admin = AdminAdminService::info($admin_log['admin_admin_id']);

            if ($admin_admin) {
                $admin_log['username'] = $admin_admin['username'];
                $admin_log['nickname'] = $admin_admin['nickname'];
            }

            $admin_log['menu_name'] = '';
            $admin_log['menu_url']  = '';
            $admin_menu = AdminMenuService::info($admin_log['admin_menu_id']);

            if ($admin_menu) {
                $admin_log['menu_name'] = $admin_menu['menu_name'];
                $admin_log['menu_url']  = $admin_menu['menu_url'];
            }

            AdminLogCache::set($admin_log_id, $admin_log);
        }

        return $admin_log;
    }

    /**
     * 管理员日志添加
     *
     * @param array $param 日志数据
     * 
     * @return void
     */
    public static function add($param = [])
    {
        $admin_menu    = AdminMenuService::info();
        $ip_info       = IpInfoService::info();
        $request_param = Request::param();

        if (isset($request_param['password'])) {
            unset($request_param['password']);
        }
        if (isset($request_param['new_password'])) {
            unset($request_param['new_password']);
        }
        if (isset($request_param['old_password'])) {
            unset($request_param['old_password']);
        }

        $param['admin_menu_id']    = $admin_menu['admin_menu_id'];
        $param['request_ip']       = $ip_info['ip'];
        $param['request_country']  = $ip_info['country'];
        $param['request_province'] = $ip_info['province'];
        $param['request_city']     = $ip_info['city'];
        $param['request_area']     = $ip_info['area'];
        $param['request_region']   = $ip_info['region'];
        $param['request_isp']      = $ip_info['isp'];
        $param['request_param']    = serialize($request_param);
        $param['request_method']   = Request::method();
        $param['create_time']      = datetime();

        Db::name('admin_log')->strict(false)->insert($param);
    }

    /**
     * 管理员日志修改
     *
     * @param array $param 日志数据
     * 
     * @return array
     */
    public static function edit($param = [])
    {
        $admin_log_id = $param['admin_log_id'];

        unset($param['admin_log_id']);

        $param['request_param'] = serialize($param['request_param']);
        $param['update_time']   = datetime();

        $res = Db::name('admin_log')
            ->where('admin_log_id', $admin_log_id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['admin_log_id'] = $admin_log_id;

        AdminLogCache::del($admin_log_id);

        return $param;
    }

    /**
     * 管理员日志删除
     *
     * @param integer $admin_log_id 日志id
     * 
     * @return array
     */
    public static function dele($admin_log_id)
    {
        $update['is_delete']   = 1;
        $update['delete_time'] = datetime();

        $res = Db::name('admin_log')
            ->where('admin_log_id', $admin_log_id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        $update['admin_log_id'] = $admin_log_id;

        AdminLogCache::del($admin_log_id);

        return $update;
    }

    /**
     * 管理员日志数量统计
     *
     * @param string $date 日期
     *
     * @return integer
     */
    public static function staNumber($date = 'total')
    {
        $key  = $date;
        $data = AdminLogCache::get($key);

        if (empty($data)) {
            $where[] = ['is_delete', '=', 0];

            if ($date == 'total') {
                $where[] = ['admin_log_id', '>', 0];
            } else {
                if ($date == 'yesterday') {
                    $yesterday = Datetime::yesterday();
                    list($sta_time, $end_time) = Datetime::datetime($yesterday);
                } elseif ($date == 'thisweek') {
                    list($start, $end) = Datetime::thisWeek();
                    $sta_time = Datetime::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = Datetime::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'lastweek') {
                    list($start, $end) = Datetime::lastWeek();
                    $sta_time = Datetime::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = Datetime::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'thismonth') {
                    list($start, $end) = Datetime::thisMonth();
                    $sta_time = Datetime::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = Datetime::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'lastmonth') {
                    list($start, $end) = Datetime::lastMonth();
                    $sta_time = Datetime::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = Datetime::datetime($end);
                    $end_time = $end_time[1];
                } else {
                    $today = Datetime::today();
                    list($sta_time, $end_time) = Datetime::datetime($today);
                }

                $where[] = ['create_time', '>=', $sta_time];
                $where[] = ['create_time', '<=', $end_time];
            }

            $data = Db::name('admin_log')
                ->field('admin_log_id')
                ->where($where)
                ->count('admin_log_id');

            AdminLogCache::set($key, $data);
        }

        return $data;
    }

    /**
     * 管理员日志日期统计
     *
     * @param array $date 日期范围
     *
     * @return array
     */
    public static function staDate($date = [])
    {
        if (empty($date)) {
            $date[0] = Datetime::daysAgo(29);
            $date[1] = Datetime::today();
        }

        $sta_date = $date[0];
        $end_date = $date[1];

        $key  = 'date:' . $sta_date . '-' . $end_date;
        $data = AdminLogCache::get($key);

        if (empty($data)) {
            $sta_time = Datetime::dateStartTime($sta_date);
            $end_time = Datetime::dateEndTime($end_date);

            $field   = "count(create_time) as num, date_format(create_time,'%Y-%m-%d') as date";
            $where[] = ['create_time', '>=', $sta_time];
            $where[] = ['create_time', '<=', $end_time];
            $group   = "date_format(create_time,'%Y-%m-%d')";

            $admin_log = Db::name('admin_log')
                ->field($field)
                ->where($where)
                ->group($group)
                ->select();

            $x_data = Datetime::betweenDates($sta_date, $end_date);
            $y_data = [];

            foreach ($x_data as $k => $v) {
                $y_data[$k] = 0;
                foreach ($admin_log as $ka => $va) {
                    if ($v == $va['date']) {
                        $y_data[$k] = $va['num'];
                    }
                }
            }

            $data['x_data'] = $x_data;
            $data['y_data'] = $y_data;
            $data['date']   = $date;

            AdminLogCache::set($key, $data);
        }

        return $data;
    }

    /**
     * 管理员日志地区统计
     *
     * @param integer $date   日期范围
     * @param string  $region 地区类型
     * @param integer $top    top排行
     *   
     * @return array
     */
    public static function staRegion($date = [], $region = 'city', $top = 20)
    {
        if (empty($date)) {
            $date[0] = Datetime::daysAgo(29);
            $date[1] = Datetime::today();
        }

        $sta_date = $date[0];
        $end_date = $date[1];

        $key  = ':' . $sta_date . '-' . $end_date . ':top:' . $top;

        if ($region == 'country') {
            $group = 'request_country';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } elseif ($region == 'province') {
            $group = 'request_province';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } elseif ($region == 'isp') {
            $group = 'request_isp';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } else {
            $group = 'request_city';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        }

        $data = AdminLogCache::get($key);

        if (empty($data)) {
            $sta_time = Datetime::dateStartTime($date[0]);
            $end_time = Datetime::dateEndTime($date[1]);

            $where[] = ['is_delete', '=', 0];
            $where[] = ['create_time', '>=', $sta_time];
            $where[] = ['create_time', '<=', $end_time];

            $admin_log = Db::name('admin_log')
                ->field($field . ', COUNT(admin_log_id) as y_data')
                ->where($where)
                ->group($group)
                ->order('y_data desc')
                ->limit($top)
                ->select();

            $x_data = [];
            $y_data = [];
            $p_data = [];

            foreach ($admin_log as $k => $v) {
                $x_data[] = $v['x_data'];
                $y_data[] = $v['y_data'];
                $p_data[] = ['value' => $v['y_data'], 'name' => $v['x_data']];
            }

            $data['x_data'] = $x_data;
            $data['y_data'] = $y_data;
            $data['p_data'] = $p_data;
            $data['date']   = $date;

            AdminLogCache::set($key, $data);
        }

        return $data;
    }
}
