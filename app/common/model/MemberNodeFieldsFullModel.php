<?php

namespace app\common\model;

use think\Model;


class MemberNodeFieldsFullModel extends Model
{

    

    

    // 表名
    protected $name = 'member_node_fields_full';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    protected $deleteTime = false;

    // 追加属性

    

    



    public function getCtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ctime']) ? $data['ctime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getMtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['mtime']) ? $data['mtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setMtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    /**
     * 获取所有的树形菜单
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    public static function getMemberNodeTree($data, $parent_id = 0, $isClear = TRUE)
    {
        static $ret = [];
        if ($isClear) {
            $ret = [];
        }
        foreach ($data as $k => $v) {
            if($v['parent_id'] == $parent_id) {
                $ret[] = $v;
                self::getMemberNodeTree($data, $v['node_id'], FALSE);
            }
        }
        return $ret;
    }

    public function membernode()
    {

        return $this->belongsTo('MemberNode', 'node_id','node_id')->setEagerlyType(0);
    }

}
