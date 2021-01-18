<?php

namespace app\common\model;

use think\Model;


class DepartmentModel extends Model
{

    // 表名
    protected $name = 'department';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    protected $deleteTime = false;

    // 追加属性
//    protected $append = [
//        'ctime_text',
//        'mtime_text'
//    ];


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
        foreach ($data as $k => $v) {
            if($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $children = self::getTreeMulti($data, $v['id'], $level+1);
                if (!empty($children)) {
                    $v['children'] = $children;
                }
                $ret[] = $v;
            }
        }
        return $ret;
    }

    public function dutyUser()
    {
        return $this->belongsTo('user','duty_user_id','id');
    }
}
