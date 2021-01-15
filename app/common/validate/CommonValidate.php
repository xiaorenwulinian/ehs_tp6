<?php

namespace app\common\validate;

use think\Validate;


class CommonValidate extends Validate
{
    protected $rule =  [
        'id'                => 'require|integer',
        'company_id'        => 'require|integer',
    ];


    protected $message  =   [

    ];

    protected $scene = [

        'detail' =>  [
            'id'
        ],

        'delete' =>  [
            'id'
        ],

        'index' =>  [
            'company_id'
        ],


    ];
}