<?php

namespace app\common\model;

use think\Model;


class DevicePatrolPointModel extends Model
{

    

    

    // 表名
    protected $name = 'device_patrol_point';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    public function company()
    {
        return $this->belongsTo('company','company_id','id');
    }

    public function companyArea()
    {
        return $this->belongsTo('companyArea','company_area_id','id');
    }






}
