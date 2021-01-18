<?php

namespace app\common\model;

use think\Model;


class EhsCourseModel extends Model
{

    

    

    // 表名
    protected $name = 'ehs_course';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;


    public function job()
    {
        return $this->belongsTo(JobModel::class,'job_id','id');
    }



}
