<?php

namespace app\common\validate;

use think\Validate;


class JobQualifyValidate extends Validate
{
    protected $rule =   [
        'id'    => 'require|integer',
        'company_id'      => 'require|integer',
        'qualify_name'    => 'require|max:255',
        'desc'            => 'require|max:255',
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
            'qualify_name',
//            'desc',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'qualify_name',
//            'desc',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}