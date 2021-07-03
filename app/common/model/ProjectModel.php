<?php
/*
 * @Description  : 案例管理模型
 * @Author       : https://github.com/skyselang
 * @Date         : 2021-06-09
 * @LastEditTime : 2021-07-03
 */

namespace app\common\model;

use think\Model;
use hg\apidoc\annotation\Field;
use hg\apidoc\annotation\AddField;
use hg\apidoc\annotation\WithoutField;
use hg\apidoc\annotation\Param;

class ProjectModel extends Model
{
    protected $name = 'project';
    protected $pk = 'project_id';

    /**
     * @Field("project_id,name,project_category_id,sort,hits,is_top,is_hot,is_rec,is_hide,create_time,update_time")
     * @AddField("img_url", type="string", require=true, default="", desc="图片链接")
     */
    public function list()
    {
    }

    /**
     * @WithoutField("imgs,files,is_delete,delete_time")
     */
    public function info()
    {
    }

    /**
     * @Field("project_category_id,name,title,,keywords,description,content,sort")
     */
    public function add()
    {
    }

    /**
     * @Field("project_id,project_category_id,name,title,,keywords,description,content,sort")
     */
    public function edit()
    {
    }

    /**
     * @Field("is_top")
     */
    public function istop()
    {
    }

    /**
     * @Field("is_hot")
     */
    public function ishot()
    {
    }

    /**
     * @Field("is_rec")
     */
    public function isrec()
    {
    }

    /**
     * @Field("is_hide")
     */
    public function ishide()
    {
    }

    /**
     * @Field("project_id")
     */
    public function id()
    {
    }

    /**
     * @Field("name")
     */
    public function name()
    {
    }

    /**
     * @Field("project_id,name,project_category_id")
     * @AddField("project_id", type="int", require=false, default="", desc="id")
     * @AddField("name", type="string", require=false, default="", desc="名称")
     * @AddField("project_category_id", type="int", require=false, default="", desc="分类id")
     */
    public function search()
    {
    }

    /**
     * @Field("name,project_category_id")
     * @AddField("name", type="string", require=false, default="", desc="名称")
     * @AddField("project_category_id", type="int", require=false, default="", desc="分类id")
     */
    public function indexList()
    {
    }

    /**
     * @Field("imgs,files")
     * @AddField("imgs", type="array", require=false, default="[]", desc="图片",
     *      @Param("name", type="string", require=true, default="", desc="名称"),
     *      @Param("path", type="string", require=true, default="", desc="路径"),
     *      @Param("url", type="string", require=true, default="", desc="链接"),
     *      @Param("uid", type="int", require=true, default="", desc="uid")
     * )
     * @AddField("files", type="array", require=false, default="[]", desc="附件",
     *      @Param("name", type="string", require=true, default="", desc="名称"),
     *      @Param("path", type="string", require=true, default="", desc="路径"),
     *      @Param("url", type="string", require=true, default="", desc="链接"),
     *      @Param("uid", type="int", require=true, default="", desc="uid")
     * )
     */
    public function imgfile()
    {
    }
}
