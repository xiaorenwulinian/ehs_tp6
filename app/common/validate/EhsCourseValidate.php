<?php

namespace app\common\validate;

use think\Validate;


class EhsCourseValidate extends Validate
{
    protected $rule =   [
        'id'                        => 'require|integer',
        'job_id'                    => 'require|integer',
        'name'                      => 'require|max:255',
        'type'                      => 'in:1,2',
        'hour'                      => 'require|integer',
        'integration'               => 'require|integer',
        'is_online'                 => 'in:0,1',
    ];

//    protected $message  =   [
//        'company_id.require'       => '公司id必须',
//        'company_id.integer'       => '公司id为整数',
//    ];

    protected $scene = [

        'index' =>  [
            'job_id',
//            'type'
        ],

        'add'  =>  [
//            'job_id',
            'name',
            'hour',
            'integration',
            'type',
            'is_online',

        ],

        'edit'   =>  [
            'id',
            'job_id',
            'name',
            'type',
            'is_online',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}