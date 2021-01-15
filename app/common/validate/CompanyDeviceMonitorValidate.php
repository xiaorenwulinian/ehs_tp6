<?php

namespace app\common\validate;

use think\Validate;


class CompanyDeviceMonitorValidate extends Validate
{
    protected $rule =   [
        'id'                         => 'require|integer',
        'company_id'                 => 'require|integer',
        'company_area_id'            => 'require|integer',
        'device_name'                => 'require|max:255',
        'type'                       => 'require|in:1,2,3',
        'device_state'               => 'in:1,2,3,4',
        'duty_user_id'               => 'require|max:255',
        'spec'                       => 'require|max:100',
        'note'                       => 'require|max:255',
        'desc'                       => 'require|max:255',
        'sort'                       => 'require|float',
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
            'company_area_id',
            'device_name',
            'type',
            'device_state',
            'duty_user_id',
            'spec',
            'note',
//            'desc',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'company_area_id',
            'device_name',
            'type',
            'device_state',
            'duty_user_id',
            'spec',
            'note',
//            'desc',

        ],

        'delete' =>  [
            'company_device_monitor_id'
        ],
    ];
}