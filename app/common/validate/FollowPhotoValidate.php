<?php

namespace app\common\validate;



class FollowPhotoValidate extends BaseValidate
{
    protected $rule =   [
        'id'                 => 'require|integer',
        'company_id'         => 'require|integer',
        'audit_status'       => 'require|in:11,21,22',



    ];

    protected $message  =   [
//        'company_id.require'       => '公司id必须',
//        'company_id.integer'       => '公司id为整数',

    ];

    protected $scene = [



        'add' =>  [
            'company_id',
            'area_id',
            'add_time',
            'risk_theme',
            'risk_desc',
            'files',
            'audit_id',
            'device_id',
//            'risk_type',
//            'risk_level',
//            'audit_time',
//            'audit_status',
//            'integral_num'

        ],
/*
 * 随拍审核
 */
        'edit' =>  [
            'id',
            'user_id',
            'company_id',
//            'area_id',
//            'add_time',
//            'risk_theme',
//            'risk_desc',
//            'files',
//            'audit_id',
//            'device_id',
            'risk_type',
            'risk_level',
//            'audit_time',
//            'audit_status',
//            'integral_num'
        ],

        'index' =>  [
            'id',
        ],


    ];
}