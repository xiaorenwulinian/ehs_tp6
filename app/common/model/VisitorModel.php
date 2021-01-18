<?php

namespace app\common\model;

use think\Model;


class VisitorModel extends Model
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
        return $this->belongsTo(CompanyAreaModel::class,'company_area_id','id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyAreaModel::class,'company_id','company_id');
    }






}
