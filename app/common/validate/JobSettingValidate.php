<?php

namespace app\common\validate;

use think\Validate;


class JobSettingValidate extends Validate
{
    protected $rule =   [
        'id'                => 'require|integer',
        'company_area_id'   => 'require|integer',
        'job_id'            => 'require|integer',
        'setting_no'        => 'require|max:60',
        'rfid_id'           => 'require|max:60',
        'camera_id'         => 'require|max:60',
        'status'            => 'require|in:1,2',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [
        ],

        'add'    =>  [
            'company_area_id',
            'job_id',
            'rfid_id',
            'camera_id',
        ],

        'edit'   =>  [
            'id',
            'company_area_id',
            'job_id',
            'rfid_id',
            'camera_id',
            'status',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}