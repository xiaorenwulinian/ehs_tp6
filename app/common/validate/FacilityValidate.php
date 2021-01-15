<?php

namespace app\common\validate;

use think\Validate;


class FacilityValidate extends Validate
{
    protected $rule =   [
        'id'    => 'require|integer',
        'company_area_id'      => 'require|integer',
        'department_id'      => 'require|integer',
        'leader_job_id'      => 'require|integer',
        'worker_job_id'      => 'require|integer',
        'name'      => 'require|max:255',
        'check_time_ids'      => 'require|max:255',
        'rfid_ids'      => 'require|max:255',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'company_area_id',
//            'duty_user_id',
            'name',
        ],

        'edit'   =>  [
            'id',
//            'company_area_id',
//            'duty_user_id',
            'name',

        ],

        'delete' =>  [
            'id'
        ],

        'detail' =>  [
            'id'
        ],
    ];
}