<?php

namespace app\common\model;

use think\Model;


class DeviceLocationPointModel extends Model
{

    

    

    // 表名
    protected $name = 'device_location_point';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = false;
    protected $deleteTime = false;


    public function getCtimeAttr($value)
    {
        if (!empty($value)) {
            return !is_numeric($value) ? $value : date("Y-m-d H:i:s", $value);
        } else {
            return '';
        }
    }

    public function locationDevice()
    {
        return $this->belongsTo('device_rfid','rfid_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo('company','company_id', 'company_id');

    }
    public function companyArea()
    {
        return $this->belongsTo('companyArea','company_area_id', 'id');

    }
    public function department()
    {
        return $this->belongsTo('department','department_id', 'department_id');

    }

    public function rfidDevice()
    {
        return $this->belongsTo('device_rfid','rfid_id', 'id');
    }

}
