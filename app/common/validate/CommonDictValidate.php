<?php

namespace app\common\validate;

use think\Validate;


class CommonDictValidate extends Validate
{
    protected $rule =   [
        'id'              => 'require|integer',
        'company_id'      => 'require|integer',
        'name'            => 'require|max:255',
        'is_default'      => 'require|max:255',
        'is_show'         => 'require|in:1,2',
        'type'            => 'require|max:255',
        'content'         => 'require',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [
//            'company_id',
            'type',
        ],

        'edit'   =>  [
            'content',
            'type',

        ],

    ];
}