<?php

namespace app\common\validate;

use think\Validate;


class DepartmentValidate extends Validate
{
    protected $rule =   [
        'id'               => 'require|integer',
        'company_id'       => 'require|integer',
        'parent_id'        => 'require|integer',
        'department_name'  => 'require|max:255',
    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
        'department_name.require'  => '名称必须',
        'department_name.max'      => '名称最多不能超过255个字符',

    ];

    protected $scene = [

        'index' =>  [
//            'company_id'
        ],

        'add'    =>  [
            'department_name'
        ],

        'edit'   =>  [
            'id',
            'department_name'
        ],

        'delete' =>  [
            'id'
        ],
    ];
}