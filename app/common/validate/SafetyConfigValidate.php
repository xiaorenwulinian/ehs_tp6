<?php

namespace app\common\validate;

use think\Validate;

//设备配置
class SafetyConfigValidate extends Validate
{
    protected $rule =   [
        'company_device_monitor_id' => 'require|integer',
        'type'                      => 'require|integer',
        'static_num'                => 'number',
        'warn_num'                  => 'number',
    ];

    protected $message  =   [
        'company_device_monitor_id' => '设备id必须',
        'type.integer'              => '设备类型为整数',
        'static_num'                => '静止时间必须为数字',
        'warn_num'                  => '报警时间必须为数字',

    ];

    protected $scene = [

        'index' =>  [
            'company_id'
        ],

        'add'    =>  [
            'company_id',
            'company_device_monitor_id',
            'type',
            'static_num',
            'warn_num',
            'name',
//            'desc',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'company_device_monitor_id',
            'type',
            'static_num',
            'warn_num',
            'name',
//            'desc',

        ],

        'delete' =>  [
            'id'
        ],
    ];

}