<?php

namespace app\common\model;

use think\Model;


class DeviceLimitedSpaceModel extends Model
{

    

    

    // 表名
    protected $name = 'device_limited_space';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    public function company()
    {
        return $this->belongsTo('company','company_id','id');
    }

    public function companyArea()
    {
        return $this->belongsTo('companyArea','company_area_id','id');
    }

    public function department()
    {
        return $this->belongsTo('department','department_id','id');
    }

    public function deviceCamera()
    {
        return $this->belongsTo('deviceCamera','camera_id','id');
    }

    public function dutyUser()
    {
        return $this->belongsTo('User','duty_user_id','id');
    }
    







}
