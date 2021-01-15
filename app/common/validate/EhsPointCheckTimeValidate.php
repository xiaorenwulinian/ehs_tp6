<?php

namespace app\common\validate;

use think\Validate;


class EhsPointCheckTimeValidate extends Validate
{
    protected $rule =   [
        'id'                        => 'require|integer',
        'company_id'                => 'require|integer',
        'company_area_id'           => 'require|integer',
        'space_num'                 => 'require|integer',
        'point_name'                => 'require|max:255',
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
            'company_area_id',
            'space_num',
            'point_name',
            'desc',
//            'sort',
            'state',
        ],

        'edit'   =>  [
            'id',
            'company_id',
            'company_area_id',
            'space_num',
            'point_name',
            'desc',
//            'sort',
            'state',
        ],

        'delete' =>  [
            'id'
        ],
    ];
}