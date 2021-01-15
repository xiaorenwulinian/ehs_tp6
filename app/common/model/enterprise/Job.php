<?php

namespace app\common\model\enterprise;

use think\Model;


class Job extends Model
{


    // 表名
    protected $name = 'job';

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


    public function ehsPoint()
    {
        return $this->hasMany('ehsPoint','job_id','id');
    }
    public function company()
    {
        return $this->belongsTo('company','company_id','id');
    }

    public function department()
    {
        return $this->belongsTo('department','department_id','id');
    }

    public function companyArea()
    {
        return $this->belongsTo('companyArea','company_area_id','id');
    }




}
