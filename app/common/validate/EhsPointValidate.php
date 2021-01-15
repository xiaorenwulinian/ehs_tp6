<?php

namespace app\common\validate;

use think\Validate;


class EhsPointValidate extends Validate
{
    protected $rule =   [
        'id'                        => 'require|integer',
        'company_id'                => 'require|integer',
        'job_id'                    => 'require|integer',
        'company_area_id'           => 'require|integer',
        'ehs_point_check_time_id'   => 'require|integer',
        'standard_id'               => 'require|integer',
        'point_name'                => 'require|max:255',
        'check_content'             => 'require|max:255',
        'desc'                      => 'require|max:255',
        'sort'                      => 'require|float',
        'state'                     => 'in:0,1',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [
            'company_id'
        ],

        'add'  =>  [
            'company_id',
            'job_id',
            'company_area_id',
            'ehs_point_check_time_id',
            'standard_id',
            'point_name',
            'check_content',
            'desc',
//            'sort',
            'state',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'job_id',
            'company_area_id',
            'ehs_point_check_time_id',
            'standard_id',
            'point_name',
            'check_content',
            'desc',
//            'sort',
            'state',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}