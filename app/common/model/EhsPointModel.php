<?php

namespace app\common\model;

use think\Model;


class EhsPointModel extends Model
{

    

    

    // 表名
    protected $name = 'ehs_point';

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

    public function job()
    {
        return $this->belongsTo(JobModel::class,'job_id','id');
    }

    public function companyArea()
    {
        return $this->belongsTo(CompanyAreaModel::class,'company_area_id','id');
    }

    public function ehsPointCheckTime()
    {
        return $this->belongsTo(EhsPointCheckTimeModel::class,'ehs_point_check_time_id','id');
    }


}
