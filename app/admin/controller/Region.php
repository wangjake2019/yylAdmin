<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 地区管理控制器
namespace app\admin\controller;

use think\facade\Request;
use app\common\service\RegionService;
use app\common\validate\RegionValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("地区管理")
 * @Apidoc\Group("index")
 * @Apidoc\Sort("70")
 */
class Region
{
    /**
     * @Apidoc\Title("地区列表")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("type", type="string", default="list", desc="返回的数据类型：list列表，tree树形")
     * @Apidoc\Param("region_pid", type="string", default="0", desc="pid")
     * @Apidoc\Param("region_id", type="string", default="", desc="id")
     * @Apidoc\Param("region_name", type="string", default="", desc="名称")
     * @Apidoc\Param("region_pinyin", type="string", default="", desc="拼音")
     * @Apidoc\Returned(ref="returnCode"),
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned("list", type="array", desc="数据列表", 
     *          @Apidoc\Returned(ref="app\common\model\RegionModel\list")
     *      )
     * )
     */
    public function list()
    {
        $type          = Request::param('type/s', 'list');
        $sort_field    = Request::param('sort_field/s', '');
        $sort_value    = Request::param('sort_value/s', '');
        $region_pid    = Request::param('region_pid/d', 0);
        $region_id     = Request::param('region_id/d', '');
        $region_name   = Request::param('region_name/s', '');
        $region_pinyin = Request::param('region_pinyin/s', '');

        if ($type == 'tree') {
            $data = RegionService::info('tree');
        } else {
            if ($region_id || $region_name || $region_pinyin) {
                if ($region_id) {
                    $where[] = ['region_id', '=', $region_id];
                }
                if ($region_name) {
                    $where[] = ['region_name', '=', $region_name];
                }
                if ($region_pinyin) {
                    $where[] = ['region_pinyin', '=', $region_pinyin];
                }
            } else {
                $where[] = ['region_pid', '=', $region_pid];
            }

            $order = [];
            if ($sort_field && $sort_value) {
                $order = [$sort_field => $sort_value];
            }

            $data = RegionService::list($where, $order);
        }

        return success($data);
    }

    /**
     * @Apidoc\Title("地区信息")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\RegionModel\id")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="app\common\model\RegionModel\info")
     * )
     */
    public function info()
    {
        $param['region_id'] = Request::param('region_id/d', '');

        validate(RegionValidate::class)->scene('info')->check($param);

        $data = RegionService::info($param['region_id']);

        if ($data['is_delete'] == 1) {
            exception('地区已被删除：' . $param['region_id']);
        }

        return success($data);
    }

    /**
     * @Apidoc\Title("地区添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\RegionModel\add")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function add()
    {
        $param['region_pid']       = Request::param('region_pid/d', 0);
        $param['region_level']     = Request::param('region_level/d', 1);
        $param['region_name']      = Request::param('region_name/s', '');
        $param['region_pinyin']    = Request::param('region_pinyin/s', '');
        $param['region_jianpin']   = Request::param('region_jianpin/s', '');
        $param['region_initials']  = Request::param('region_initials/s', '');
        $param['region_citycode']  = Request::param('region_citycode/s', '');
        $param['region_zipcode']   = Request::param('region_zipcode/s', '');
        $param['region_longitude'] = Request::param('region_longitude/s', '');
        $param['region_latitude']  = Request::param('region_latitude/s', '');
        $param['region_sort']      = Request::param('region_sort/d', 1000);

        if (empty($param['region_pid'])) {
            $param['region_pid'] = 0;
        }

        if (empty($param['region_level'])) {
            $param['region_level'] = 1;
        }

        validate(RegionValidate::class)->scene('add')->check($param);

        $data = RegionService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("地区修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\RegionModel\edit")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function edit()
    {
        $param['region_id']        = Request::param('region_id/d', '');
        $param['region_pid']       = Request::param('region_pid/d', 0);
        $param['region_level']     = Request::param('region_level/d', 1);
        $param['region_name']      = Request::param('region_name/s', '');
        $param['region_pinyin']    = Request::param('region_pinyin/s', '');
        $param['region_jianpin']   = Request::param('region_jianpin/s', '');
        $param['region_initials']  = Request::param('region_initials/s', '');
        $param['region_citycode']  = Request::param('region_citycode/s', '');
        $param['region_zipcode']   = Request::param('region_zipcode/s', '');
        $param['region_longitude'] = Request::param('region_longitude/s', '');
        $param['region_latitude']  = Request::param('region_latitude/s', '');
        $param['region_sort']      = Request::param('region_sort/d', 1000);

        if (empty($param['region_pid'])) {
            $param['region_pid'] = 0;
        }

        if (empty($param['region_level'])) {
            $param['region_level'] = 1;
        }

        validate(RegionValidate::class)->scene('edit')->check($param);

        $data = RegionService::edit($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("地区删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\RegionModel\dele")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function dele()
    {
        $param['region_id'] = Request::param('region_id/d', '');

        validate(RegionValidate::class)->scene('dele')->check($param);

        $data = RegionService::dele($param['region_id']);

        return success($data);
    }
}
