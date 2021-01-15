<?php

namespace app\common\validate;

use think\Validate;


class DeviceCameraValidate extends Validate
{
    protected $rule =   [
        'id'                => 'require|integer',
        'company_area_id'   => 'require|integer',
        'department_id'     => 'require|integer',
        'device_no'         => 'require|max:60',
        'specification'     => 'require|max:60',
        'brand'             => 'require|max:60',
        'install_location'  => 'require|max:255',
        'ip'                => 'require|max:20',
        'start_time'        => 'require|dateFormat:Y-m-d H:i:s',
        'device_status'     => 'require|in:1,2',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [
        ],

        'add'    =>  [
            'company_area_id',
            'install_location',
            'specification',
            'brand',
            'ip',
            'start_time',
            'device_status',
        ],

        'edit'   =>  [
            'id',
//            'company_area_id',
            'specification',
            'brand',
            'ip',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}