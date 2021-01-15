<?php

namespace app\common\service;

use app\common\model\enterprise\CompanyDeviceMonitor;


/**
 *
 * Class DepartmentService
 * @package app\common\service
 */
class CompanyDeviceMonitorService {

    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['device_name'])) {
            $where['device_name'] = ['like', "%{$params['device_name']}%"];
        }

        $count = CompanyDeviceMonitor::where($where)->count();

        $data = CompanyDeviceMonitor::where($where)
            ->limit($offset, $pageSize)
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
            $count = CompanyDeviceMonitor::where([
                    'company_id'        => $params['company_id'],
                    'company_area_id'   => $params['company_area_id'],
                    'device_name'       => $params['device_name'],
                ])
                ->count();

            if ($count) {
                throw new \Exception("同一区域设备名称不能重复");
            }

            CompanyDeviceMonitor::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }
        return result_successed();

    }

    public function edit($params)
    {
        try {

            $data = CompanyDeviceMonitor::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($data->device_name != $params['device_name']) {

                $count = CompanyDeviceMonitor::where([
                    'company_id'        => $params['company_id'],
                    'company_area_id'   => $params['company_area_id'],
                    'device_name'       => $params['device_name'],
                ])
                    ->count();

                if ($count) {
                    throw new \Exception("部门名称不能重复");
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function delete($id)
    {
        $data = CompanyDeviceMonitor::find($id);

        if (!$data) {
            return result_failed("数据不存在");
        }
        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }
        return result_successed();

    }


}