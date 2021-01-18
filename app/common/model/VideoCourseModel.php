<?php

namespace app\common\model;

use app\common\constant\UploadConstant;
use think\Model;


class VideoCourseModel extends Model
{

    

    

    // 表名
    protected $name = 'video_course';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function setContentAttr($value)
    {
        return ltrim($value, '/');
    }





}
