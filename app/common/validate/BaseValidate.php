<?php

namespace app\common\validate;

use think\Validate;

//设备配置
class BaseValidate extends Validate
{


    protected function isMobile($value, $rule, $data)
    {

        if (preg_match("/^1[0-9]{10}$/i", $value)) {
            return true;
        }
        return "手机号码格式错误";

    }

}