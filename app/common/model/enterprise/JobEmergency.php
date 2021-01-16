<?php

namespace app\common\model\enterprise;

use think\Model;


class JobEmergency extends Model
{

    

    

    // 表名
    protected $name = 'job_emergency';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

 /*   public function job()
    {
        return $this->belongsTo('job','job_id','id');
    }*/
    public function department()
    {
        return $this->belongsTo('department','department_id','id');
    }

    







}
