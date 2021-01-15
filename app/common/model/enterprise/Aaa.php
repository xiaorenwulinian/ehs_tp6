<?php

namespace app\common\model\enterprise;

use think\Model;


class Aaa extends Model
{



    // 表名
    protected $name = 'aaa';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'timestamp';

    protected $dateFormat = 'Y-m-d H:i:s';

    // 定义时间戳字段名
//    protected $createTime = 'ctime';
//    protected $updateTime = false;
    protected $deleteTime = false;


    public function getAtAttr($value)
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



}
