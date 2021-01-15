<?php

namespace app\common\service;

use app\common\model\enterprise\EhsPoint;


/**
 *
 * Class EhsPointService
 * @package app\common\service
 */
class EhsPointService {

    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['point_name'])) {
            $where['point_name'] = ['like', "%{$params['point_name']}%"];
        }

        $count = EhsPoint::where($where)->count();

        $data = EhsPoint::with([
            'job',
            'companyArea',
            'ehsPointCheckTime'
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $data = collection($data)->toArray();

        foreach ($data as &$v) {
            $v['job_name']                  = $v['job']['job_name'] ?? '';
            $v['company_area_name']         = $v['company_area']['area_name'] ?? '';
            $v['ehs_point_check_time_name'] = $v['ehs_point_check_time']['point_name'] ?? '';

            $v['standard_name'] = 'name_' . $v['standard_id'] ?? '';

            unset($v['sort']);
            unset($v['is_deleted']);
            unset($v['job']);
            unset($v['company_area']);
            unset($v['ehs_point_check_time']);
        }
//        dd($data);

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }

    public function add($params)
    {
        try {

            EhsPoint::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function edit($params)
    {
        try {

            $data = EhsPoint::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function delete($id)
    {
        $data = EhsPoint::find($id);
        if (!$data) {
            return result_failed("数据不存在");
        }
        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return  result_failed($e->getMessage());
        }

        return true;

    }


}