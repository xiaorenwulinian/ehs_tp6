<?php

namespace app\common\library;


class ArrayLib
{

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
}
