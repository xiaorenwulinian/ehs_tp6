<?php

namespace app\common\validate;

use think\Validate;


class VisitorValidate extends BaseValidate
{
    protected $rule =   [
        'id'                => 'require|integer',
        'company_area_id'   => 'require|integer',
        'visitor_name'      => 'require|max:60',
        'receiver'          => 'require|max:60',
        'visitor_idcard'    => 'require|max:60',
        'phone'             => 'require|isMobile',
        'reasons'           => 'require|max:255',
        'visitor_no'        => 'require|max:60',
        'arrive_time'       => 'require|dateFormat:Y-m-d H:i:s',
        'leave_time'        => 'require|max:60',
        'bracelet_status'   => 'require|in:1,2',

    ];

    protected $message  =   [

    ];

    protected $scene = [

        'index' =>  [

        ],

        'add'    =>  [
            'company_area_id',
            'visitor_name',
            'receiver',
            'visitor_idcard',
            'phone',
            'reasons',
            'arrive_time',
            'bracelet_status'
        ],

        'edit'   =>  [
            'id',
//            'device_no',
            'name',
            'ip',
//            'identify_code',
//            'line_code',
            'type',
            'scene',

        ],

        'delete' =>  [
            'id'
        ],
    ];
}