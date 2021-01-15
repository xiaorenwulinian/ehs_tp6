<?php

namespace app\common\model\enterprise;

use think\Model;


class JobAbility extends Model
{

    

    

    // 表名
    protected $name = 'job_ability';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    public function company()
    {
        return $this->belongsTo('company','company_id','company_id');
    }
}
