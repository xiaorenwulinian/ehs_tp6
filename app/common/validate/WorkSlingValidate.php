<?php

namespace app\common\validate;


class WorkSlingValidate extends BaseValidate
{
    protected $rule =   [
        'id'                    => 'require|integer',
        'company_id'            => 'require|integer',
        'apply_department_id'   => 'require|integer',
        'company_area_id'       => 'require|integer',
        'work_device_id'        => 'require|integer',
//        'work_type_id'          => 'require|integer',
        'work_level_id'         => 'require|integer',
//        'work_address'          => 'require|max:255',
        'start_time'            => 'require|date',
        'end_time'              => 'require|date',
        'other_work'            => 'require|max:255',
        'operate_type'          => 'require|in:1,2,3',
        'work_user_id'          => 'require|max:255',
        'duty_department_id'    => 'require|integer',
        'monitor_user_id'       => 'require|integer',
        'confirm_user_id'       => 'require|integer',
        'charge_user_id'        => 'require|integer',
        'out_work_user_id'          => 'require|max:255',
        'out_duty_department_id'    => 'require|integer',
        'out_monitor_user_id'       => 'require|integer',
        'out_confirm_user_id'       => 'require|integer',
        'out_charge_user_id'        => 'require|integer',
//        'work_content'         => 'require|max:255',
//        'attachments'          => 'require|max:255',
//        'photo_files'          => 'require|max:255',



    ];

    protected $message  =   [
        'company_id.require'       => '公司id必须',
        'company_id.integer'       => '公司id为整数',
    ];

    protected $scene = [

        'index' =>  [
//            'company_id'
        ],

        'add'    =>  [
            'apply_department_id',
            'company_area_id',
            'work_device_id',
//            'work_type_id',
            'work_level_id',
//            'work_address',
            'start_time',
            'end_time',
            'other_work',
            'operate_type',

//            'work_user_id',
//            'duty_department_id',
//            'monitor_user_id',
//            'confirm_user_id',
//            'charge_user_id',
//
//            'out_work_user_id',
//            'out_duty_department_id',
//            'out_monitor_user_id',
//            'out_confirm_user_id',
//            'out_charge_user_id' ,

        ],

        'edit'   =>  [
            'id',
            'apply_department_id',
            'company_area_id',
//            'work_type_id',
            'work_level_id',
            'work_address',
            'start_time',
            'end_time',
            'other_work',
            'operate_type',
//            'work_user_id',
//            'duty_department_id',
//            'monitor_user_id',
//            'confirm_user_id',
//            'charge_user_id',
//            'work_content',
//            'out_work_user_id',
//            'out_duty_department_id',
//            'out_monitor_user_id',
//            'out_confirm_user_id',
//            'out_charge_user_id' ,
        ],

        'delete' =>  [
            'id'
        ],
    ];
}