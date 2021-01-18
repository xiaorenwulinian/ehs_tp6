<?php

namespace app\common\model;

use think\Model;


class JobSettingModel extends Model
{

    

    

    // 表名
    protected $name = 'job_setting';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function companyArea()
    {
        return $this->belongsTo(CompanyAreaModel::class,'company_area_id','id');
    }
    public function job()
    {
        return $this->belongsTo(JobModel::class,'job_id','id');
    }
    public function rfidDevice()
    {
        return $this->belongsTo(DeviceRfidModel::class,'rfid_id','id');
    }


    public function deviceCamera()
    {
        return $this->belongsTo(DeviceCameraModel::class,'camera_id','id');
    }







}
