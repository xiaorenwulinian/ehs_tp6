<?php

namespace app\common\model;

use think\Model;


class OcTestPlanModel extends Model
{

    

    

    // 表名
    protected $table = 'oc_test_plan';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class,'department_id','id');
    }

    public function job()
    {
        return $this->belongsTo(JobModel::class,'job_id','id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class,'user_id','id');
    }











}
