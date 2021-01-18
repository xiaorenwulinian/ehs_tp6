<?php

namespace app\common\model;

use think\Model;


class SafetyRecordWarningModel extends Model
{

    

    

    // 表名
    protected $name = 'safety_record_warning';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'atime_text',
        'sleep_time_text',
        'wake_time_text',
        'ctime_text'
    ];



    public function getAtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['atime']) ? $data['atime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getSleepTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['sleep_time']) ? $data['sleep_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getWakeTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['wake_time']) ? $data['wake_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getCtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ctime']) ? $data['ctime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setAtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setSleepTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setWakeTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setCtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'company_id','id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class,'user_ids','id');
    }

    public function companyArea()
    {
        return $this->belongsTo(CompanyAreaModel::class,'company_area_id','id');
    }

    public function companyDeviceMonitor()
    {
        return $this->belongsTo(CompanyDeviceMonitorModel::class,'company_device_monitor_id','id');
    }

    public function job()
    {
        return $this->belongsTo(JobModel::class,'job_ids','id');
    }

}
