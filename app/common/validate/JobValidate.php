<?php

namespace app\common\validate;

use think\Validate;


class JobValidate extends Validate
{
    protected $rule =   [
        'id'                => 'require|integer',
        'department_id'         => 'require|integer',
        'company_id'            => 'require|integer',
        'job_name'              => 'require|max:255',

        'forbid_area_id'        => 'require|integer',
        'special_work_id'       => 'require|integer',
        'auth_config'           => 'require|max:255',
        'harm_factor_id'        => 'require|max:255',

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
            'department_id',
            'job_name',
            'forbid_area_id',
            'special_work_id',
            'auth_config',
            'harm_factor_id',
        ],

        'edit'   =>  [
            'id',
            'department_id',
            'job_name',
            'forbid_area_id',
            'special_work_id',
            'auth_config',
            'harm_factor_id',
        ],

        'delete' =>  [
            'id'
        ],

    ];
}