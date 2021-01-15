<?php

namespace app\common\validate;

use think\Validate;


class BraceletDeviceValidate extends Validate
{
    protected $rule =   [
        'id'            => 'require|integer',
        'name'          => 'require|max:60',
        'device_no'     => 'require|max:60',
        'specification' => 'require|max:60',
    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'name',
            'specification',
            'device_status'
        ],

        'edit'   =>  [
            'id',
            'name',
            'specification',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}