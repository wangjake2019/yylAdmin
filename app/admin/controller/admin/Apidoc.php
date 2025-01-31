<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 接口文档控制器
namespace app\admin\controller\admin;

use app\common\service\admin\ApidocService;
use hg\apidoc\annotation as Apidocs;

/**
 * @Apidocs\Title("接口文档")
 * @Apidocs\Group("admin")
 * @Apidocs\Sort("60")
 */
class Apidoc
{
    /**
     * @Apidocs\Title("接口文档")
     * @Apidocs\Header(ref="headerAdmin")
     * @Apidocs\Returned(ref="returnCode")
     * @Apidocs\Returned(ref="returnData")
     */
    public function apidoc()
    {
        $data = ApidocService::apidoc();

        return success($data);
    }
}
