<?php

namespace app\common\model;

use think\Model;


class UserIntegralLogModel extends Model
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
        return $this->belongsTo(CompanyModel::class,'company_id','company_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class,'user_id','user_id');
    }
    







}
