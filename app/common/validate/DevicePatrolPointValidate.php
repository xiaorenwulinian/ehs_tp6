<?php

namespace app\common\validate;

use think\Validate;


class DevicePatrolPointValidate extends Validate
{
    protected $rule =   [
        'id'    => 'require|integer',
        'name'      => 'require|max:60',
        'company_area_id'    => 'require|integer',
        'device_status'      => 'require|in:1,2',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [
//            'company_area_id'
        ],

        'add'    =>  [
            'company_area_id',
            'name',
        ],

        'edit'   =>  [
            'id',
//            'company_area_id',
            'name',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}