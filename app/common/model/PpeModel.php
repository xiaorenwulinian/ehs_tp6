<?php

namespace app\common\model\enterprise;

use app\common\model\JobModel;
use think\Model;


class PpeModel extends Model
{

    

    

    // 表名
    protected $name = 'ppe';
    
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
