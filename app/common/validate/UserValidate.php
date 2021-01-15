<?php

namespace app\common\validate;



class UserValidate extends BaseValidate
{
    protected $rule =   [
        'id'            => 'require|integer',
//        'company_id'    => 'require|integer',
        'department_id'    => 'require|integer',
        'job_id'    => 'require|integer',
        'job_role_label_id'    => 'require|integer',
        'user_status'    => 'require|integer',
        'company_name'  => 'require',
        'account'       => 'require|max:25',
        'username'      => 'require|max:25',
        'nickname'      => 'require|max:25',
        'password'      => 'require|max:25',
        'email'         => 'require|max:25',
        'mobile'        => 'require|isMobile',
        'avatar'        => 'require',
        'sex'           => 'require|in:0,1,2',
        'smscode'       => 'require',
        'birthday'      => 'require|date',
        'user_type'     => 'require|in:0,1',

    ];

    protected $message  =   [
//        'company_id.require'       => '公司id必须',
//        'company_id.integer'       => '公司id为整数',

    ];

    protected $scene = [

        'login' =>  [
            'username',
            'password',
            'company_name',
        ],

        'mobile_login' =>  [
            'username',
            'password',
            'company_name',
        ],



        'add' =>  [
            'company_id',
            'username',
//            'nickname',
            'password',
            'mobile',

            'department_id',
            'job_id',
            'job_role_label_id',
            'user_status',

//            'email',
//            'avatar',
//            'sex',
//            'birthday',
        ],

        'edit' =>  [
            'id',

            'username',
//            'nickname',
            'password',
            'mobile',
            'department_id',
            'job_id',
            'job_role_label_id',
            'user_status',
//            'email',
//            'avatar',
//            'sex',
//            'birthday',
        ],

        'index' =>  [
            'company_id',
        ],


    ];
}