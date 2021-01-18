<?php

namespace app\common\service;

use app\common\model\CheckRateModel;

/**
 *
 * Class CheckRateService
 * @package app\common\service
 */
class CheckRateService {

    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['check_rate_name'])) {
            $where['check_rate_name'] = ['like', "%{$params['check_rate_name']}%"];
        }

        $count = CheckRateModel::where($where)->count();

        $data = CheckRateModel::where($where)
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

            CheckRateModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function edit($params)
    {
        try {

            $data = CheckRateModel::find($params['id']);
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
        $data = CheckRateModel::find($id);
        if (!$data) {
            return result_failed("数据不存在");
        }
        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return  result_failed($e->getMessage());
        }

        return result_successed();

    }


}