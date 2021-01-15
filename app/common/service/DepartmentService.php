<?php

namespace app\common\service;

use app\common\model\enterprise\Department;
use think\DB;
use app\common\traits\SingletonTrait;


/**
 *
 * Class DepartmentService
 * @package app\common\service
 */
class DepartmentService {


    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['department_name'])) {
            $where['department_name'] = ['like', "%{$params['department_name']}%"];
        }

        $count = Department::where($where)->count();

        $data = Department::with([
            'dutyUser'
        ])
            ->where($where)
//            ->limit($offset, $pageSize)
            ->order('parent_id', 'asc')
            ->select();

        $data = collection($data)->toArray();
        $newData= [];
        foreach ($data as $v) {
            $temp = $v;
            $temp['duty_user_name']  = $v['duty_user']['username'] ?? '';

            unset($temp['duty_user']);
            unset($temp['is_deleted']);
            unset($temp['parent_associate']);
            array_push($newData, $temp);
        }
        $list = \app\common\model\enterprise\CompanyArea::getTreeMulti($newData);

        $ret = [
            'count' => $count,
            'list'  => $list,
        ];

        return result_successed($ret);

    }

    public function add($params)
    {
        try {
            $count = Department::where([
                    'company_id'      => $params['company_id'],
                    'department_name' => $params['department_name'],
                ])
                ->count();

            if ($count) {
                throw new \Exception("部门名称不能重复");
            }


            if ($params['parent_id'] == 0) {
                $params['cur_level'] = 1;
                $parent_associate = 0;

            } else {
                $dept = Department::get($params['parent_id']);
                $parent_associate = $dept['parent_associate'];
                $params['cur_level'] = $dept['cur_level'] + 1;
            }

            $params['parent_associate'] = $parent_associate;

            $data = Department::create($params);

            $data->parent_associate = $parent_associate . ',' . $data->id;
            $data->save();

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function edit($params)
    {
        try {

            $department = Department::get($params['id']);
            if (!$department) {
                throw new \Exception("未发现该部门");
            }

            if ($department->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($department->department_name != $params['department_name']) {
                $count = Department::where([
                        'company_id'      => $params['company_id'],
                        'department_name' => $params['department_name'],
                    ])
                    ->count();

                if ($count) {
                    throw new \Exception("部门名称不能重复");
                }
            }

            $department->department_name = $params['department_name'];
            $department->duty_user_id = $params['duty_user_id'];
            if (!empty($params['parent_id'])) {
                $department->parent_id = $params['parent_id'];
            }

            $department->save();

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function delete($id)
    {
        $data = Department::find($id);
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