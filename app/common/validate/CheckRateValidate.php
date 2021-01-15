<?php

namespace app\common\validate;

use think\Validate;


class CheckRateValidate extends Validate
{
    protected $rule =   [
        'id'             => 'require|integer',
        'company_id'                => 'require|integer',
        'check_rate_name'           => 'require|max:255',
        'desc'                      => 'require|max:255',
        'sort'                      => 'require|float',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [
            'company_id'
        ],

        'add'  =>  [
            'company_id',
            'check_rate_name',
            'desc',
//            'sort',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'check_rate_name',
            'desc',
//            'sort',
        ],

        'delete' =>  [
            'check_rate_id'
        ],
    ];
}