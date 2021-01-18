<?php

namespace app\common\model;

use think\Model;


class WorkHighModel extends Model
{


    /*
     * 特殊作业类型
     * $work_typeData = [
            1 => '在阵风风力为6级（风速10.8米/秒）及以上情况下进行的强风高处作业',
            2 => '在高温或低温环境下进行的异温高处作业',
            3 => '在降雪时进行的雪天高处作业',
            4 => '在降雨时进行的雨天高处作业',
            5 => '在室外完全采用人工照明进行的夜间高处作业',
            6 => '在接近或接触带电体条件下进行的带电高处作业',
            7 => '在无立足点或无牢靠立足点的条件下进行的悬空高处作业',
            8 => '其他',
        ];
    */
    const HIGH_TYPE_ARR = [
        1 => '一般高处作业',
        2 => '特殊高处作业',
    ];

    /*
        * 分级：
        1、作业高度在2米至5米时，称为一级高处作业。
        2、作业高度在5米以上至15米时，称为二级高处作业。
        3、作业高度在15米以上至30米时，称为三级高处作业。
        4、作业高度在30米以上时，称为特级高处作业。

       类别：
       在阵风风力为6级（风速10.8米/秒）及以上情况下进行的强风高处作业；
       在高温或低温环境下进行的异温高处作业；
       在降雪时进行的雪天高处作业；
       在降雨时进行的雨天高处作业；
       在室外完全采用人工照明进行的夜间高处作业；
       在接近或接触带电体条件下进行的带电高处作业；
       在无立足点或无牢靠立足点的条件下进行的悬空高处作业。
       其他
        */

    /* $highLevelData = [
         1 => '作业高度在2米至5米时，称为一级高处作业',
         2 => '作业高度在5米以上至15米时，称为二级高处作业',
         3 => '作业高度在15米以上至30米时，称为三级高处作业',
         4 => '作业高度在30米以上时，称为特级高处作业',
     ];*/
    const HIGH_LEVEL_ARR = [
            1 => '一级高处作业',
            2 => '二级高处作业',
            3 => '三级高处作业',
            4 => '特级高处作业',
        ];

    // 表名
    protected $name = 'work_high';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;


    public function applyDepartment()
    {
        return $this->belongsTo(DepartmentModel::class, 'apply_department_id','id');
    }

    public function companyArea()
    {
        return $this->belongsTo(CompanyArea::class, 'company_area_id','id');
    }

}
