<?php

namespace app\common\model\enterprise;

use think\Model;


class UserJob extends Model
{

    

    

    // 表名
    protected $name = 'user_job';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';


    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    protected $deleteTime = false;

    

    



    public function getCtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ctime']) ? $data['ctime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
