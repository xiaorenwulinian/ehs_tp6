<?php

namespace app\common\model;

use think\Model;


class FacilityModel extends Model
{

    const CHECK_TIME_ARR = [
        1 => '上岗',
        2 => '离岗',
        3 => '交换班',
    ];

    

    // 表名
    protected $name = 'facility';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 追加属性
    protected $append = [

    ];



    public function companyArea()
    {
        return $this->belongsTo(CompanyAreaModel::class,'company_area_id','id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'company_id','id');
    }

    public function leaderJob()
    {
        return $this->belongsTo(JobModel::class,'leader_job_id','id');

    }


    public function workerJob()
    {
        return $this->belongsTo(JobModel::class,'worker_job_id','id');

    }



    public function department()
    {
        return $this->belongsTo(DepartmentModel::class,'department_id','id');

    }


}
