<?php

namespace app\common\model\enterprise;

use think\Model;


class EhsCourse extends Model
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
        return $this->belongsTo('job','job_id','id');
    }



}
