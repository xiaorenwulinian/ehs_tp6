<?php

namespace app\common\validate;

use think\Validate;


class DeviceSpaceValidate extends Validate
{
    protected $rule =   [
        'id'                => 'require|integer',
        'company_area_id'   => 'require|integer',
        'department_id'     => 'require|integer',
        'duty_user_id'      => 'require|integer',
        'name'              => 'require|max:60',
        'camera_id'         => 'require|max:20',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [
        ],

        'add'    =>  [
            'company_area_id',
            'department_id',
            'duty_user_id',
            'camera_id',
            'name'
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