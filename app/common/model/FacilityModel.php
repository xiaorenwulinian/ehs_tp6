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
        return $this->belongsTo('company_area','company_area_id','id');
    }

    public function company()
    {
        return $this->belongsTo('company','company_id','id');
    }

    public function leaderJob()
    {
        return $this->belongsTo('job','leader_job_id','id');

    }


    public function workerJob()
    {
        return $this->belongsTo('job','worker_job_id','id');

    }



    public function department()
    {
        return $this->belongsTo('department','department_id','id');

    }


}
