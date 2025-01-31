<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 用户管理验证器
namespace app\common\validate\admin;

use think\Validate;
use app\common\service\admin\UserService;

class UserValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'admin_user_id' => ['require'],
        'username'      => ['require', 'checkUsername', 'length' => '2,32'],
        'nickname'      => ['require', 'checkNickname', 'length' => '1,32'],
        'password'      => ['require', 'length' => '6,18'],
        'phone'         => ['mobile', 'checkPhone'],
        'email'         => ['email', 'checkEmail'],
        'avatar'        => ['require', 'file', 'image', 'fileExt' => 'jpg,png,gif,jpeg', 'fileSize' => '102400'],
    ];

    // 错误信息
    protected $message = [
        'username.require' => '请输入账号/手机/邮箱',
        'username.length'  => '账号长度为2至32个字符',
        'nickname.require' => '请输入昵称',
        'nickname.length'  => '昵称长度为1至32个字符',
        'password.require' => '请输入密码',
        'password.length'  => '密码长度为6至18个字符',
        'phone.mobile'     => '请输入正确的手机号码',
        'email.email'      => '请输入正确的邮箱地址',
        'avatar.require'   => '请选择图片',
        'avatar.file'      => '请选择图片文件',
        'avatar.image'     => '请选择图片格式文件',
        'avatar.fileExt'   => '请选择jpg、png、gif格式图片',
        'avatar.fileSize'  => '请选择大小小于100kb图片',
    ];

    // 验证场景
    protected $scene = [
        'id'      => ['admin_user_id'],
        'info'    => ['admin_user_id'],
        'login'   => ['username', 'password'],
        'add'     => ['username', 'nickname', 'password', 'phone', 'email'],
        'edit'    => ['admin_user_id', 'username', 'nickname', 'phone', 'email'],
        'dele'    => ['admin_user_id'],
        'super'   => ['admin_user_id'],
        'disable' => ['admin_user_id'],
        'rule'    => ['admin_user_id'],
        'pwd'     => ['admin_user_id', 'password'],
        'avatar'  => ['avatar'],

    ];

    // 验证场景定义：登录
    protected function scenelogin()
    {
        return $this->only(['username', 'password'])
            ->remove('username', ['length', 'checkUsername'])
            ->remove('password', ['length']);
    }

    // 验证场景定义：修改
    protected function sceneedit()
    {
        return $this->only(['admin_user_id', 'username', 'nickname', 'email', 'phone'])
            ->append('admin_user_id', ['checkAdminUserIsSuper']);
    }

    // 验证场景定义：删除
    protected function scenedele()
    {
        return $this->only(['admin_user_id'])
            ->append('admin_user_id', ['checkAdminUserIsDelete', 'checkAdminUserRoleMenu']);
    }

    // 验证场景定义：是否超管
    protected function scenesuper()
    {
        return $this->only(['admin_user_id'])
            ->append('admin_user_id', ['checkAdminUserIsSuper']);
    }

    // 验证场景定义：是否禁用
    protected function scenedisable()
    {
        return $this->only(['admin_user_id'])
            ->append('admin_user_id', ['checkAdminUserIsDisable']);
    }

    // 验证场景定义：分配权限
    protected function scenerule()
    {
        return $this->only(['admin_user_id'])
            ->append('admin_user_id', ['checkAdminUserIsSuper']);
    }

    // 验证场景定义：重置密码
    protected function scenepwd()
    {
        return $this->only(['admin_user_id', 'password'])
            ->append('admin_user_id', ['checkAdminUserIsSuper']);
    }

    // 自定义验证规则：账号是否已存在
    protected function checkUsername($value, $rule, $data = [])
    {
        if (isset($data['admin_user_id'])) {
            $where_user[] = ['admin_user_id', '<>', $data['admin_user_id']];
        }
        $where_user[] = ['username', '=', $data['username']];
        $admin_user = UserService::list($where_user, 1, 1, [], 'admin_user_id');
        if ($admin_user['list']) {
            return '账号已存在：' . $data['username'];
        }

        return true;
    }

    // 自定义验证规则：昵称是否已存在
    protected function checkNickname($value, $rule, $data = [])
    {
        if (isset($data['admin_user_id'])) {
            $where_user[] = ['admin_user_id', '<>', $data['admin_user_id']];
        }
        $where_user[] = ['nickname', '=', $data['nickname']];
        $admin_user = UserService::list($where_user, 1, 1, [], 'admin_user_id');
        if ($admin_user['list']) {
            return '昵称已存在：' . $data['nickname'];
        }

        return true;
    }

    // 自定义验证规则：手机是否已存在
    protected function checkPhone($value, $rule, $data = [])
    {
        if (isset($data['admin_user_id'])) {
            $where_user[] = ['admin_user_id', '<>', $data['admin_user_id']];
        }
        $where_user[] = ['phone', '=', $data['phone']];
        $admin_user = UserService::list($where_user, 1, 1, [], 'admin_user_id');
        if ($admin_user['list']) {
            return '手机已存在：' . $data['phone'];
        }

        return true;
    }

    // 自定义验证规则：邮箱是否已存在
    protected function checkEmail($value, $rule, $data = [])
    {
        if (isset($data['admin_user_id'])) {
            $where_user[] = ['admin_user_id', '<>', $data['admin_user_id']];
        }
        $where_user[] = ['email', '=', $data['email']];
        $admin_user = UserService::list($where_user, 1, 1, [], 'admin_user_id');
        if ($admin_user['list']) {
            return '邮箱已存在：' . $data['email'];
        }

        return true;
    }

    // 自定义验证规则：用户是否已分配角色或菜单
    protected function checkAdminUserRoleMenu($value, $rule, $data = [])
    {
        $admin_user = UserService::info($data['admin_user_id']);
        if ($admin_user['admin_role_ids'] || $admin_user['admin_menu_ids']) {
            return '请在[权限]中取消所有角色和菜单后再删除';
        }

        return true;
    }

    // 自定义验证规则：用户是否超管
    protected function checkAdminUserIsSuper($value, $rule, $data = [])
    {
        $admin_is_super = admin_is_super(admin_user_id());
        $admin_user_id  = admin_is_super($data['admin_user_id']);
        if (!$admin_is_super && $admin_user_id) {
            return '无法对系统用户进行操作';
        }

        return true;
    }

    // 自定义验证规则：用户是否禁用
    protected function checkAdminUserIsDisable($value, $rule, $data = [])
    {
        $admin_is_super = admin_is_super($data['admin_user_id']);
        if ($admin_is_super) {
            return '无法对系统用户进行操作';
        }

        return true;
    }

    // 自定义验证规则：用户删除
    protected function checkAdminUserIsDelete($value, $rule, $data = [])
    {
        $admin_is_super = admin_is_super($data['admin_user_id']);
        if ($admin_is_super) {
            return '无法对系统用户进行操作';
        }

        return true;
    }
}
