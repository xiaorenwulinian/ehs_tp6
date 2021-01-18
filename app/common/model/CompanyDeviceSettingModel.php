<?php

namespace app\common\model;

use think\Model;


class CompanyDeviceSettingModel extends Model
{

    const TYPE = [
        'single machine',
        'device setting',
        'production line',
    ];

    

    // 表名
    protected $name = 'company_device_setting';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    protected $deleteTime = false;

    public function getCtimeAttr($value)
    {
        if (!empty($value)) {
            return !is_numeric($value) ? $value : date("Y-m-d H:i:s", $value);
        } else {
            return '';
        }
    }

    public function getMtimeAttr($value)
    {
        if (!empty($value)) {
            return !is_numeric($value) ? $value : date("Y-m-d H:i:s", $value);
        } else {
            return '';
        }
    }

    public function dutyUser()
    {
        return $this->belongsTo('user','duty_user_id','user_id');
    }

    public function user()
    {
        return $this->belongsTo('user','duty_user_id','user_id');
    }

    public function company()
    {
        return $this->belongsTo('company','company_id','company_id');
    }

    public function companyArea()
    {
        return $this->belongsTo('companyArea','company_area_id','id');
    }

}
