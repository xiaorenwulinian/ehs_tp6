<?php

namespace app\common\validate;

use think\Validate;


        class EmerPlanValidate extends Validate
{
    protected $rule =   [
        'id'                    => 'require|integer',
        'company_id'            => 'require|integer',
        'department_id'         => 'require|integer',
        'job_id'                => 'require|integer',
        'name'                  => 'require|max:255',
        'evaluate_type'         => 'in:1,2',
        'excess_plan'           => 'require|max:255',

    ];


    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'department_id',
            'job_id' ,
            'name',
            'evaluate_type',
            'excess_plan',
        ],

        'edit'   =>  [
            'id',
//            'department_id',
//            'job_id' ,
            'name',
            'evaluate_type',
            'excess_plan',
        ],

        'delete' =>  [
            'id'
        ],

    ];
}