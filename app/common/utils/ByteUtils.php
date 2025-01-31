<?php
// +----------------------------------------------------------------------
// | yylAdmin 前后分离，简单轻量，免费开源，开箱即用，极简后台管理系统
// +----------------------------------------------------------------------
// | Copyright https://gitee.com/skyselang All rights reserved
// +----------------------------------------------------------------------
// | Gitee: https://gitee.com/skyselang/yylAdmin
// +----------------------------------------------------------------------

// 字节
namespace app\common\utils;

class ByteUtils
{
    /**
     * 字节格式化
     *
     * @param integer $num 字节数值
     *
     * @return integer
     */
    public static function format($num = 0)
    {
        $p = 0;
        $format = 'bytes';
        if ($num > 0 && $num < 1024) {
            $p = 0;
            return number_format($num) . ' ' . $format;
        }
        if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        }
        if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }

        $num /= pow(1024, $p);

        return number_format($num, 2) . ' ' . $format;
    }

    /**
     * 字节转换
     *
     * @param array $param 类型、数值
     *
     * @return array
     */
    public static function tran($param)
    {
        $type  = $param['type'] ?: 'B';
        $value = $param['value'] ?: 1024;

        $hex_b = 8;
        $hex_B = 1024;

        $data['type']  = $type;
        $data['value'] = $value;

        if ($type == 'B') {
            $data['B']  = $value;
            $data['b']  = $data['B'] * $hex_b;
            $data['KB'] = $data['B'] / $hex_B;
            $data['MB'] = $data['KB'] / $hex_B;
            $data['GB'] = $data['MB'] / $hex_B;
            $data['TB'] = $data['GB'] / $hex_B;
        } elseif ($type == 'KB') {
            $data['KB'] = $value;
            $data['B']  = $data['KB'] * $hex_B;
            $data['b']  = $data['B'] * $hex_b;
            $data['MB'] = $data['KB'] / $hex_B;
            $data['GB'] = $data['MB'] / $hex_B;
            $data['TB'] = $data['GB'] / $hex_B;
        } elseif ($type == 'MB') {
            $data['MB'] = $value;
            $data['KB'] = $data['MB'] * $hex_B;
            $data['B']  = $data['KB'] * $hex_B;
            $data['b']  = $data['B']  * $hex_b;
            $data['GB'] = $data['MB'] / $hex_B;
            $data['TB'] = $data['GB'] / $hex_B;
        } elseif ($type == 'GB') {
            $data['GB'] = $value;
            $data['MB'] = $data['GB'] * $hex_B;
            $data['KB'] = $data['MB'] * $hex_B;
            $data['B']  = $data['KB'] * $hex_B;
            $data['b']  = $data['B'] * $hex_b;
            $data['TB'] = $data['GB'] / $hex_B;
        } elseif ($type == 'TB') {
            $data['TB'] = $value;
            $data['GB'] = $data['TB'] * $hex_B;
            $data['MB'] = $data['GB'] * $hex_B;
            $data['KB'] = $data['MB'] * $hex_B;
            $data['B']  = $data['KB'] * $hex_B;
            $data['b']  = $data['B'] * $hex_b;
        } else {
            $data['b']  = $value;
            $data['B']  = $data['b'] / $hex_b;
            $data['KB'] = $data['B'] / $hex_B;
            $data['MB'] = $data['KB'] / $hex_B;
            $data['GB'] = $data['MB'] / $hex_B;
            $data['TB'] = $data['GB'] / $hex_B;
        }

        return $data;
    }
}
