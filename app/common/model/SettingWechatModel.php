<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 微信设置模型
namespace app\common\model;

use think\Model;
use hg\apidoc\annotation\Field;
use hg\apidoc\annotation\AddField;

class SettingWechatModel extends Model
{
    protected $name = 'setting_wechat';

    /**
     * @Field("name,origin_id,qrcode,appid,appsecret,url,token,encoding_aes_key,encoding_aes_type")
     * @AddField("qrcode_url",type="string",default="",desc="二维码链接")
     */
    public function offiInfo()
    {
    }

    /**
     * @Field("name,origin_id,qrcode,appid,appsecret,url,token,encoding_aes_key,encoding_aes_type")
     */
    public function offiEdit()
    {
    }

    /**
     * @Field("name,origin_id,qrcode,appid,appsecret")
     * @AddField("qrcode_url",type="string",default="",desc="二维码链接")
     */
    public function miniInfo()
    {
    }

    /**
     * @Field("name,origin_id,qrcode,appid,appsecret")
     */
    public function miniEdit()
    {
    }
}
