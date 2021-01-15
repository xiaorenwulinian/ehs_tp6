<?php

namespace app\common\service;



use think\Db;

class MemberNodeService
{

    /**
     * 获取全功能列表菜单
     * return void
     */
    public function getMemberNodeList()
    {

        $field = 'node_id,actionurl,name,parent_id,is_top,icon,level';

        $list = Db::name('member_node')
            ->field($field)
            ->where(['state' => 1])
            ->order(['level' => 'asc', 'sort' => 'asc'])
            ->select();

        $data = [];
        foreach ($list as $v) {
            if ($v['parent_id'] == 0) {

                foreach ($list as $v2) {
                    if ($v2['parent_id'] == $v['node_id']) {
                        $v['children'][] = $v2;
                    }
                }
                $data[] = $v;
            }
        }

        $ret = [
            'list' => $data
        ];

        return result_successed($ret);


    }


}