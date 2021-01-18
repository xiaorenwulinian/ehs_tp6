<?php

namespace app\common\model;

use think\Model;


class CompanyAreaModel extends Model
{

    

    

    // 表名
    protected $name = 'company_area';

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

    /**
     * 获取子集的最大级别
     */
    public static function getChildrenMaxLevel()
    {


    }


    /**
     * 获取所有的树形菜单
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    public static function getTree($data, $parent_id = 0, $level = 0, $isClear = TRUE)
    {
        static $ret = [];
        if ($isClear) {
            $ret = [];
        }
        foreach ($data as $k => $v) {
            if($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                self::getTree($data, $v['id'], $level+1, FALSE);
            }
        }
        return $ret;
    }

    /**
     * 获取所有的树形菜单
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    public static function getTreeMulti($data, $parent_id = 0, $level = 1)
    {
        $ret = [];

        foreach ($data as $k => &$v) {
            if($v['parent_id'] == $parent_id) {
                $v['level'] = $level;

                $newData = self::getTreeMulti($data, $v['id'], $level+1);
                if (!empty($newData)) {
                    $v['children'] = $newData;
                }
                array_push($ret, $v);

            }
        }
        return $ret;
    }

    public function director()
    {
        return $this->belongsTo('User','director_id','id');
    }

    public function job()
    {
        return $this->belongsTo('Job','job_id','id');
    }

    public function checkRate()
    {
        return $this->belongsTo('checkRate','check_rate_id','id');
    }

}
