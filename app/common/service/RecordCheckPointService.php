<?php

namespace app\common\service;

use app\common\model\enterprise\RecordCheckPoint;


/**
 *
 * Class JobService
 * @package app\common\service
 */
class RecordCheckPointService {


    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        if (!empty($params['ehs_point_id'])) {
            $where['ehs_point_id'] = ['=', $params['ehs_point_id']];
        }

        if (!empty($params['user_id'])) {
            $where['user_id'] = ['=', $params['user_id']];
        }

        if (!empty($params['ehs_point_check_time_id'])) {
            $where['ehs_point_check_time_id'] = ['=', $params['ehs_point_check_time_id']];
        }

        if (!empty($params['a_begin_time'])) {
            $begintime = strtotime($params['a_begin_time']);
        } else {
            $begintime = 0;
        }

        if (!empty($params['a_end_time'])) {
            $endtime = strtotime($params['a_end_time']) + 3600;
        } else {
            $endtime = time();
//            $endtime = 0;
        }
        $where['atime'] = ['between', [$begintime, $endtime]];

       /* if (!empty($begintime) || !empty($endtime)) {

            if (!empty($begintime) && !empty($endtime)) {
                $where['atime'] = ['between', [$begintime, $endtime]];
            } else {
                if (!empty($begintime)) {
                    $where['atime'] = ['>=', $begintime];
                } else {
                    $where['atime'] = ['<=', $endtime];
                }
            }
        }*/


        $count = RecordCheckPoint::where($where)->count();

        $data = RecordCheckPoint::where($where)
            ->limit($offset, $pageSize)
            ->order('record_check_point_id','desc')
            ->select();
//        dd(collection($data)->toArray());

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return $ret;

    }


}