<?php

namespace app\common\model;

use think\Model;


class StrategyGoalModel extends Model
{

    // 表名
    protected $name = 'strategy_goal';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 追加属性
    protected $append = [

    ];


    public function department()
    {
        return $this->belongsTo(DepartmentModel::class,'department_id','id');
    }


    public function user()
    {
        return $this->belongsTo(UserModel::class, 'director_id', 'id');
    }



}
