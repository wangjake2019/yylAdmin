<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 用户管理模型
namespace app\common\model\admin;

use think\Model;
use hg\apidoc\annotation\Field;
use hg\apidoc\annotation\AddField;

class UserModel extends Model
{
    protected $name = 'admin_user';

    /**
     * @Field("admin_user_id")
     */
    public function id()
    {
    }

    /**
     * @Field("username")
     */
    public function username()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,sort,is_disable,is_super,login_num,login_ip,login_time")
     */
    public function list()
    {
    }

    /**
     * @AddField("admin_token", type="string", desc="AdminToken")
     */
    public function info()
    {
    }

    /**
     * @Field("username,nickname,password,phone,email,remark,sort")
     */
    public function add()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,remark,sort")
     */
    public function edit()
    {
    }

    /**
     * @Field("admin_user_id")
     */
    public function dele()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("avatar_file", type="file", require=true, desc="头像文件")
     */
    public function avatar()
    {
    }

    /**
     * @Field("admin_user_id,password")
     */
    public function pwd()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("admin_role_ids", type="array", require=true, desc="角色id,eg:[1,2]")
     * @AddField("admin_menu_ids", type="array", require=true, desc="菜单id,eg:[1,2]")
     */
    public function rule()
    {
    }

    /**
     * @Field("admin_user_id,is_disable")
     */
    public function disable()
    {
    }

    /**
     * @Field("admin_user_id,is_super")
     */
    public function super()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("admin_token", type="string", desc="AdminToken")
     */
    public function login()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,sort,is_disable,is_super")
     */
    public function user()
    {
    }
}
