<?php

namespace app\common\validate;

use think\Validate;


class CompanyAreaValidate extends Validate
{
    protected $rule =   [
        'id'            => 'require|integer',
        'parent_id'     => 'require|integer',
        'director_id'   => 'require|integer',
        'name'          => 'require|max:255',

    ];

    protected $message  =   [

    ];

    protected $scene = [
        'add'    =>  [
            'name',
            'parent_id',
            'director_id',
        ],
        'edit'   =>  [
            'id',
            'name',
            'parent_id',
            'director_id',
        ],
        'delete' =>  [
            'id'
        ],
        'index' =>  [

        ],
    ];
}