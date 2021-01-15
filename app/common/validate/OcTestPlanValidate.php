<?php

namespace app\common\validate;

use think\Validate;


class OcTestPlanValidate extends Validate
{
    protected $rule =   [
       'id'             => 'require|integer',
       'company_id'     => 'require|integer',
       'user_id'        => 'require|integer',
       'department_id'  => 'require|integer',
       'job_id'         => 'require|integer',
       'before_time'    => 'require|date',
       'next_time'      => 'require|date',
       'test_item'      => 'require|max:255',
       'is_job'         => 'require|in:1,2,3',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'user_id',
            'department_id',
            'job_id',
//            'before_time',
//            'next_time',
//            'test_item',
            'is_job'
        ],

        'edit'   =>  [
            'id',
            'user_id',
            'department_id',
            'job_id',
            'before_time',
            'next_time',
//            'test_item',
            'is_job'

        ],

        'delete' =>  [
            'id'
        ],

        'detail' =>  [
            'id'
        ],
    ];
}