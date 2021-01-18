<?php

namespace app\common\model;

use think\Model;


class LabelModel extends Model
{


    // 表名
    protected $name = 'label';


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

    public function configCategory()
    {
//        return $this->belongsTo("configCategory",'cate_name_id','config_category_id');
        return $this->hasOne("configCategory",'config_category_id','cate_name_id');
    }


}
