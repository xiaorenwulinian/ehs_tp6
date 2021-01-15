<?php

namespace app\common\validate;

use think\Validate;


class NumberConfigValidate extends Validate
{
    protected $rule =  [
        'id'                => 'require|integer',
        'identify'          => 'require|max:255',
        'ext_select'        => 'require|in:0,1',
        'serial_length'     => 'require|integer',
        'prefix_alp'        => 'require|max:10',
    ];


    protected $message  =   [

    ];

    protected $scene = [

        'detail' =>  [
            'identify'
        ],

        'edit' =>  [
            'identify',
            'ext_select',
            'serial_length',
            'prefix_alp',
        ],

        'delete' =>  [
            'id'
        ],

        'index' =>  [
            'company_id'
        ],


    ];
}