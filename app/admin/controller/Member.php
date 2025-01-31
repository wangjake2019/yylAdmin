<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 会员管理控制器
namespace app\admin\controller;

use think\facade\Request;
use app\common\validate\MemberValidate;
use app\common\service\MemberService;
use app\common\service\UploadService;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("会员管理")
 * @Apidoc\Group("index")
 * @Apidoc\Sort("10")
 */
class Member
{
    /**
     * @Apidoc\Title("会员列表")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="paramPaging")
     * @Apidoc\Param(ref="paramSort")
     * @Apidoc\Param(ref="paramSearch")
     * @Apidoc\Param(ref="paramDate")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="returnPaging"),
     *      @Apidoc\Returned("list", type="array", desc="数据列表", 
     *          @Apidoc\Returned(ref="app\common\model\MemberModel\list")
     *      )
     * )
     */
    public function list()
    {
        $page         = Request::param('page/d', 1);
        $limit        = Request::param('limit/d', 10);
        $sort_field   = Request::param('sort_field/s', '');
        $sort_value   = Request::param('sort_value/s', '');
        $search_field = Request::param('search_field/s', '');
        $search_value = Request::param('search_value/s', '');
        $date_field   = Request::param('date_field/s', '');
        $date_value   = Request::param('date_value/a', '');

        $where[] = ['is_delete', '=', 0];
        if ($search_field && $search_value) {
            if ($search_field == 'member_id') {
                $where[] = [$search_field, '=', $search_value];
            } else {
                $where[] = [$search_field, 'like', '%' . $search_value . '%'];
            }
        }
        if ($date_field && $date_value) {
            $where[] = [$date_field, '>=', $date_value[0] . ' 00:00:00'];
            $where[] = [$date_field, '<=', $date_value[1] . ' 23:59:59'];
        }

        $order = [];
        if ($sort_field && $sort_value) {
            $order = [$sort_field => $sort_value];
        }

        $data = MemberService::list($where, $page, $limit, $order);

        return success($data);
    }

    /**
     * @Apidoc\Title("会员信息")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\id")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="app\common\model\MemberModel\info")
     * )
     */
    public function info()
    {
        $param['member_id'] = Request::param('member_id/d', '');

        validate(MemberValidate::class)->scene('info')->check($param);

        $data = MemberService::info($param['member_id']);

        if ($data['is_delete'] == 1) {
            exception('会员已被删除：' . $param['member_id']);
        }

        unset($data['password'], $data['token']);

        return success($data);
    }

    /**
     * @Apidoc\Title("会员添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\add")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function add()
    {
        $param['avatar']    = Request::param('avatar/s', '');
        $param['username']  = Request::param('username/s', '');
        $param['nickname']  = Request::param('nickname/s', '');
        $param['password']  = Request::param('password/s', '');
        $param['phone']     = Request::param('phone/s', '');
        $param['email']     = Request::param('email/s', '');
        $param['region_id'] = Request::param('region_id/d', 0);
        $param['remark']    = Request::param('remark/s', '');
        $param['sort']      = Request::param('sort/d', 250);

        validate(MemberValidate::class)->scene('add')->check($param);

        $data = MemberService::add($param, 'post');

        return success($data);
    }

    /**
     * @Apidoc\Title("会员修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\edit")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function edit()
    {
        $param['member_id'] = Request::param('member_id/d', '');
        $param['avatar']    = Request::param('avatar/s', '');
        $param['username']  = Request::param('username/s', '');
        $param['nickname']  = Request::param('nickname/s', '');
        $param['phone']     = Request::param('phone/s', '');
        $param['email']     = Request::param('email/s', '');
        $param['region_id'] = Request::param('region_id/d', 0);
        $param['remark']    = Request::param('remark/s', '');
        $param['sort']      = Request::param('sort/d', 250);

        validate(MemberValidate::class)->scene('edit')->check($param);

        $data = MemberService::edit($param, 'post');

        return success($data);
    }

    /**
     * @Apidoc\Title("会员删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\dele")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function dele()
    {
        $param['member_id'] = Request::param('member_id/d', '');

        validate(MemberValidate::class)->scene('dele')->check($param);

        $data = MemberService::dele($param['member_id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("会员上传头像")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\ParamType("formdata")
     * @Apidoc\Param(ref="paramFile")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnFile")
     */
    public function avatar()
    {
        $param['avatar'] = Request::file('file');

        validate(MemberValidate::class)->scene('avatar')->check($param);

        $data = UploadService::upload($param['avatar'], 'member/avatar');

        return success($data, '上传成功');
    }

    /**
     * @Apidoc\Title("会员重置密码")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\pwd")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function pwd()
    {
        $param['member_id'] = Request::param('member_id/d', '');
        $param['password']  = Request::param('password/s', '');

        validate(MemberValidate::class)->scene('pwd')->check($param);

        $data = MemberService::pwd($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("会员是否禁用")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\common\model\MemberModel\disable")
     * @Apidoc\Returned(ref="returnCode")
     * @Apidoc\Returned(ref="returnData")
     */
    public function disable()
    {
        $param['member_id']  = Request::param('member_id/d', '');
        $param['is_disable'] = Request::param('is_disable/d', 0);

        validate(MemberValidate::class)->scene('disable')->check($param);

        $data = MemberService::disable($param);

        return success($data);
    }
}
