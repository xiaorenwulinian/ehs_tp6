<?php

namespace app\common\service;

use app\common\model\enterprise\JobAvoid;
use app\common\model\StrategyGoalModel;
use app\common\traits\SingletonTrait;


/**
 *
 * Class StrategyService
 * @package app\common\service
 */
class StrategyService {


    public function goalIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];
//        $where['type'] = ['=', $params['type']];

        $count = StrategyGoalModel::where($where)->count();

        $data = StrategyGoalModel::with([
            'user',
            'department',
        ])
        ->where($where)
//            ->with('job')
//            ->field('sort,is_deleted',true)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $v['director_name'] = $v['user']['username'] ?? '';
            $v['username'] = $v['user']['username'] ?? '';
            $v['level'] = $v['level'] == 1 ? '公司' : '部门';
            if ($v['type'] ==1){
                $v['type'] = '环境';
            }elseif($v['type'] ==2){
                $v['type'] = '职业健康安全';
            }else{
                $v['type'] = '能源';
            }

            unset($v['user']);
            unset($v['department']);
            array_push($newData, $v);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return  result_successed($ret);

    }

    public function goalAdd($params)
    {

        try {

            StrategyGoalModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function goalEdit($params)
    {
        try {

            $data = StrategyGoalModel::find($params['id']);
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

    public function goalDelete($id)
    {
        $data = StrategyGoalModel::find($id);
        if (!$data) {
            return result_failed("数据不存在");
        }
        try {
            StrategyGoalModel::where('id', $id)
                ->delete();
        } catch (\Exception $e) {
            return  result_failed($e->getMessage());
        }

        return result_successed();

    }


}