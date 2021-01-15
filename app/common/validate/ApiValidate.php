<?php

namespace app\common\validate;

use think\Validate;

class ApiValidate extends Validate
{
    protected $rule = [];
    protected $message = [
        //'token.require' => 'token必传',
        //登录 , 注册
        'account.require' => '用户名必须填写',
        'account.max'     => '名称最多不能超过25个字符',
        'password.require' => '密码不能为空',
        'mobile.require' => '手机号码不能为空',
        'company_name.require' => '企业名称不允许为空',
        'smscode.require' => '验证码不能为空',

        //公司，部门相关
        'page.require' => '页码不能为空',
        'page_size.require' => '每页显示数据条数不能为空',

        //新增员工
        'username.require' => '用户真实姓名必须填写',
        'mobile.require' => '员工手机号码不能为空',
        'nickname.require' => '用户名不能为空',
        'department_id.require' => '部门department_id为必传参数',
        'company_id.require' => '公司company_id为必传参数',

        //删除员工
        'user_id.require' => '用户id必须填写',

         //全功能管理相关
        'actionurl.require' => '链接地址不能为空',

    ];
}
