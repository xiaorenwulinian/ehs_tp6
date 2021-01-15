<?php

namespace app\common\model\enterprise;

use think\Model;


class Visitor extends Model
{

    

    

    // 表名
    protected $name = 'visitor';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 追加属性
    protected $append = [

    ];

    public function getLeaveTimeAttr($value)
    {
        if (!empty($value)) {
            return !is_numeric($value) ? $value : date("Y-m-d H:i:s", $value);
        } else {
            return '';
        }
    }


    public function companyArea()
    {
        return $this->belongsTo('company_area','company_area_id','id');
    }

    public function company()
    {
        return $this->belongsTo('company','company_id','company_id');
    }






}
