<?php

namespace app\common\model\enterprise;

use think\Model;


class DeviceCamera extends Model
{


    // 表名
    protected $name = 'device_camera';
    
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
    







}
