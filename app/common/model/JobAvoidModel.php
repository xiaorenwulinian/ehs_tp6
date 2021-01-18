<?php

namespace app\common\model;

use think\Model;


class JobAvoidModel extends Model
{

    

    

    // 表名
    protected $name = 'job_avoid';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'timestamp';
    protected $dateFormat = 'Y-m-d H:i:s';

//    protected $autoWriteTimestamp = 'timestamp';
//    protected $pk = "ehs_point_check_time_id";

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


}
