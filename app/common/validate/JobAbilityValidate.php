<?php

namespace app\common\validate;

use think\Validate;


class JobAbilityValidate extends Validate
{
    protected $rule =   [
        'id'              => 'require|integer',
        'company_id'      => 'require|integer',
        'name'            => 'require|max:255',
        'is_default'      => 'require|max:255',
        'is_show'         => 'require|in:1,2',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [
            'company_id'
        ],

        'add'    =>  [
            'company_id',
            'name',
//            'desc',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'name',
//            'is_default',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}