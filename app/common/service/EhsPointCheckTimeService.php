<?php

namespace app\common\service;

use app\common\model\EhsPointCheckTimeModel;

/**
 *
 * Class EhsPointCheckTimeService
 * @package app\common\service
 */
class EhsPointCheckTimeService
{

    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['point_name'])) {
            $where['point_name'] = ['like', "%{$params['point_name']}%"];
        }

        $count = EhsPointCheckTimeModel::where($where)->count();

        $data = EhsPointCheckTimeModel::where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }

    public function add($params)
    {
        try {

            $data = EhsPointCheckTimeModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed(compact('data'));

    }

    public function edit($params)
    {
        try {

            $data = EhsPointCheckTimeModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该部门");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed(compact('data'));

    }


}