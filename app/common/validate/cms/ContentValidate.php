<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 内容管理验证器
namespace app\common\validate\cms;

use think\Validate;
use app\common\service\cms\ContentService;

class ContentValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'content'     => ['require', 'array'],
        'content_id'  => ['require'],
        'category_id' => ['require'],
        'name'        => ['require'],
        'content'     => ['require'],
        'image'       => ['require', 'file', 'image', 'fileExt' => 'jpg,png,gif,jpeg', 'fileSize' => '512000'],
        'video'       => ['require', 'file', 'fileExt' => 'mp4,avi,wmv,rm,ram,mov,swf,flv,mpg,mpeg', 'fileSize' => '52428800'],
        'file'        => ['require', 'file', 'fileSize' => '10485760'],
        'is_top'      => ['require', 'in' => '0,1'],
        'is_hot'      => ['require', 'in' => '0,1'],
        'is_rec'      => ['require', 'in' => '0,1'],
        'is_hide'     => ['require', 'in' => '0,1'],
        'sort_field'  => ['checkSort'],
        'sort_value'  => ['checkSort'],
    ];

    // 错误信息
    protected $message = [
        'category_id.require' => '请选择分类',
        'name.require'        => '请输入名称',
        'content.require'     => '请输入内容',
        'image.require'       => '请选择图片',
        'image.file'          => '请选择上传图片',
        'image.image'         => '请选择图片格式文件',
        'image.fileExt'       => '请选择jpg、png、jpeg格式图片',
        'image.fileSize'      => '请选择小于500kb的图片',
        'video.require'       => '请选择视频',
        'video.file'          => '请选择上传视频',
        'video.fileExt'       => '请选择mp4,avi,wmv,rm,ram,mov,swf,flv,mpg,mpeg格式视频',
        'video.fileSize'      => '请选择小于50M的视频',
        'file.require'        => '请选择文件',
        'file.file'           => '请选择上传文件',
        'file.fileSize'       => '请选择小于10M的文件',
        'is_top.in'           => '是否置顶 1是0否',
        'is_hot.in'           => '是否热门 1是0否',
        'is_rec.in'           => '是否推荐 1是0否',
        'is_hide.in'          => '是否隐藏 1是0否',
    ];

    // 验证场景
    protected $scene = [
        'info'   => ['content_id'],
        'add'    => ['category_id', 'name'],
        'edit'   => ['content_id', 'category_id', 'name'],
        'dele'   => ['content'],
        'istop'  => ['content', 'is_top'],
        'ishot'  => ['content', 'is_hot'],
        'isrec'  => ['content', 'is_rec'],
        'ishide' => ['content', 'is_hide'],
        'image'  => ['image'],
        'video'  => ['video'],
        'file'   => ['file'],
        'sort'   => ['sort_field', 'sort_value'],
    ];

    // 自定义验证规则：排序字段是否存在，排序类型是否为asc、desc
    protected function checkSort($value, $rule, $data = [])
    {
        $sort_field = $data['sort_field'];
        $sort_value  = $data['sort_value'];

        $field_exist = ContentService::tableFieldExist($sort_field);
        if (!$field_exist) {
            return '排序字段sort_field：' . $sort_field . ' 不存在';
        }

        if (!empty($sort_value) && $sort_value != 'asc' && $sort_value != 'desc') {
            return '排序类型sort_value只能为asc升序或desc降序';
        }

        return true;
    }
}
