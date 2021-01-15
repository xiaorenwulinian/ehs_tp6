<?php

namespace app\common\validate;

use think\Validate;


        class PpeValidate extends Validate
{
    protected $rule =   [
        'id'                    => 'require|integer',
        'company_id'            => 'require|integer',
//        'job_id'                => 'require|integer',
        'ppe_type_id'           => 'require|integer',
        'name'                  => 'require|max:255',
        'type'                  => 'require|max:255',
        'spec'                  => 'require|max:255',
        'brand'                 => 'require|max:255',
        'firm_rate'             => 'require|integer',
        'attribute'             => 'in:1,2',
        'firm_rate_type'        => 'in:1,2',

    ];


    protected $scene = [

        'index' =>  [

        ],

        'add'   =>  [
//            'job_id',
            'ppe_type_id',
            'name',
            'type',
            'spec',
            'brand',
            'firm_rate',
            'attribute',
            'firm_rate_type',

        ],

        'edit'   =>  [
            'id',
//            'job_id',
            'ppe_type_id',
            'name',
            'type',
            'spec',
            'brand',
            'firm_rate',
            'attribute',
            'firm_rate_type',
        ],

        'delete' =>  [
            'id'
        ],

    ];
}