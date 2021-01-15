<?php

namespace app\common\validate;

use think\Validate;


class RfidDeviceValidate extends Validate
{
    protected $rule =   [
        'id'    => 'require|integer',
        'name'      => 'require|max:60',
        'ip'      => 'require|max:20',
        'type'      => 'require|in:1,2',
        'scene'      => 'require|in:1,2,3',
        'device_no'      => 'require|max:60',
        'identify_code'      => 'require|max:60',
        'line_code'      => 'require|max:60',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
//            'device_no',
            'name',
            'ip',
//            'identify_code',
//            'line_code',
            'type',
            'scene',
        ],

        'edit'   =>  [
            'id',
//            'device_no',
            'name',
            'ip',
//            'identify_code',
//            'line_code',
            'type',
            'scene',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}