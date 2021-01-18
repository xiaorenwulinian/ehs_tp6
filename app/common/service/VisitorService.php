<?php

namespace app\common\service;

use app\common\model\enterprise\VisitorModel;
use app\common\traits\SingletonTrait;


/**
 *
 * Class VisitorService
 * @package app\common\service
 */
class VisitorService {

    use SingletonTrait;


    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['visitor_name'])) {
            $where['visitor_name'] = ['like', "%{$params['visitor_name']}%"];
        }

        $count = VisitorModel::where($where)->count();

        $data = VisitorModel::where($where)
//            ->with('job')
//            ->field('sort,is_deleted',true)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            unset($temp['is_deleted']);
            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function add($params)
    {
        try {

            $params['visitor_no'] = (new NumberConfigService())->generatorNoVisitor($params['company_id']);
            $params['arrive_time'] = date('Y-m-d H:i:s');
            $params['bracelet_status'] = 1;

            VisitorModel::create($params);

            // todo push info to bind bracelet


        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }



}