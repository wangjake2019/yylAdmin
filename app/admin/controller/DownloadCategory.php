<?php
/*
 * @Description  : 下载分类控制器
 * @Author       : https://github.com/skyselang
 * @Date         : 2021-06-08
 * @LastEditTime : 2021-07-01
 */

namespace app\admin\controller;

use think\facade\Request;
use hg\apidoc\annotation as Apidoc;
use app\common\validate\DownloadCategoryValidate;
use app\common\service\DownloadCategoryService;

/**
 * @Apidoc\Title("下载分类")
 * @Apidoc\Group("adminCms")
 * @Apidoc\Sort("999")
 */
class DownloadCategory
{
    /**
     * @Apidoc\Title("下载分类列表")
     * @Apidoc\Method("GET")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned("list", type="array", desc="数据列表", 
     *          @Apidoc\Returned(ref="app\common\model\DownloadCategoryModel\list")
     *      )
     * )
     */
    public function list()
    {
        $data['list'] = DownloadCategoryService::list();

        return success($data);
    }

    /**
     * @Apidoc\Title("下载分类信息")
     * @Apidoc\Method("GET")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\DownloadCategoryModel\id")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="app\common\model\DownloadCategoryModel\info")
     * )
     */
    public function info()
    {
        $param['download_category_id'] = Request::param('download_category_id/d', '');

        validate(DownloadCategoryValidate::class)->scene('info')->check($param);

        $data = DownloadCategoryService::info($param['download_category_id']);
        if ($data['is_delete'] == 1) {
            exception('下载分类已被删除：' . $param['download_category_id']);
        }

        return success($data);
    }

    /**
     * @Apidoc\Title("下载分类添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\DownloadCategoryModel\add")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function add()
    {
        $param['download_category_pid'] = Request::param('download_category_pid/d', 0);
        $param['category_name']         = Request::param('category_name/s', '');
        $param['title']                 = Request::param('title/s', '');
        $param['keywords']              = Request::param('keywords/s', '');
        $param['description']           = Request::param('description/s', '');
        $param['imgs']                  = Request::param('imgs/a', []);
        $param['sort']                  = Request::param('sort/d', 200);

        validate(DownloadCategoryValidate::class)->scene('add')->check($param);

        $data = DownloadCategoryService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("下载分类修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\DownloadCategoryModel\edit")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function edit()
    {
        $param['download_category_id']  = Request::param('download_category_id/d', '');
        $param['download_category_pid'] = Request::param('download_category_pid/d', 0);
        $param['category_name']         = Request::param('category_name/s', '');
        $param['title']                 = Request::param('title/s', '');
        $param['keywords']              = Request::param('keywords/s', '');
        $param['description']           = Request::param('description/s', '');
        $param['imgs']                  = Request::param('imgs/a', []);
        $param['sort']                  = Request::param('sort/d', 200);

        validate(DownloadCategoryValidate::class)->scene('edit')->check($param);

        $data = DownloadCategoryService::edit($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("下载分类删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("download_category", type="array", require=true, desc="下载分类列表")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function dele()
    {
        $param['download_category'] = Request::param('download_category/a', '');

        validate(DownloadCategoryValidate::class)->scene('dele')->check($param);

        $data = DownloadCategoryService::dele($param['download_category']);

        return success($data);
    }

    /**
     * @Apidoc\Title("下载分类上传图片")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\ParamType("formdata")
     * @Apidoc\Param(ref="ParamFile")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnFile")
     */
    public function upload()
    {
        $param['type'] = Request::param('type/s', 'image');
        $param['file'] = Request::file('file');

        $param[$param['type']] = $param['file'];
        if ($param['type'] == 'image') {
            validate(DownloadCategoryValidate::class)->scene('image')->check($param);
        } elseif ($param['type'] == 'video') {
            validate(DownloadCategoryValidate::class)->scene('video')->check($param);
        } else {
            validate(DownloadCategoryValidate::class)->scene('file')->check($param);
        }

        $data = DownloadCategoryService::upload($param);

        return success($data, '上传成功');
    }

    /**
     * @Apidoc\Title("下载分类是否隐藏")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("download_category", type="array", require=true, desc="下载分类列表")
     * @Apidoc\Param(ref="app\common\model\DownloadCategoryModel\ishide")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function ishide()
    {
        $param['download_category'] = Request::param('download_category/a', '');
        $param['is_hide']           = Request::param('is_hide/d', 0);

        validate(DownloadCategoryValidate::class)->scene('ishide')->check($param);

        $data = DownloadCategoryService::ishide($param);

        return success($data);
    }
}
