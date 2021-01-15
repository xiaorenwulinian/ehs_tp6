<?php

namespace app\common\model\enterprise;

use think\Model;


class UserIntegralLog extends Model
{

    

    

    // 表名
    protected $name = 'user_integral_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    // 追加属性
    protected $append = [

    ];

    public function company()
    {
        return $this->belongsTo('company','company_id','company_id');
    }

    public function user()
    {
        return $this->belongsTo('user','user_id','user_id');
    }
    







}
