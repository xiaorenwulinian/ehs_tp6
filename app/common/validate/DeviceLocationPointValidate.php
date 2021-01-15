<?php

namespace app\common\validate;

use think\Validate;


class DeviceLocationPointValidate extends Validate
{
    protected $rule =   [
        'id'                 => 'require|integer',
        'device_no'          => 'require|max:60',
        'company_area_id'    => 'require|integer',
        'department_id'      => 'require|integer',
        'radius'             => 'require|integer',
        'rfid_id'            => 'require|integer',
        'device_status'      => 'require|in:1,2',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
//            'device_no',
            'company_area_id',
            'radius',
            'rfid_id',
//            'device_status',
            'department_id',
        ],

        'edit'   =>  [
            'id',
//            'device_no',
            'company_area_id',
            'radius',
            'rfid_id',
            'device_status',
            'department_id',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}