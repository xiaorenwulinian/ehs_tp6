<?php

namespace app\common\validate;

use think\Validate;

class DeviceIdentifyValidate extends Validate
{
    protected $rule =   [
        'id'            => 'require|integer',
        'name'          => 'require|max:60',
        'device_no'     => 'require|max:60',
        'duty_user_id'  => 'require|integer',
        'device_status' => 'require|in:1,2',
    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'name',
            'duty_user_id',
            'device_status'
        ],

        'edit'   =>  [
            'id',
            'name',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}
