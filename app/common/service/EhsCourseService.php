<?php

namespace app\common\service;

use app\common\model\enterprise\EhsCourse;
use think\DB;
use app\common\traits\SingletonTrait;


/**
 *
 * Class EhsCourseService
 * @package app\common\service
 */
class EhsCourseService {


    public function courseIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        if (!empty($params['job_id'])) {
            $where['job_id'] = ['=', $params['job_id']];
        }
        $count = EhsCourse::where($where)->count();

        $data = EhsCourse::with([
            'job',
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $data = collection($data)->toArray();
//        dd($data);

        foreach ($data as &$v) {
            if (1 == $v['type']){
                $v['job_name'] = '全部';
            }else{
                $v['job_name'] = $v['job']['job_name'] ?? '';
            }
            $v['type_str'] = $v['type'] == 1 ? '通用课程' : '专业课程';
            $v['is_online_str'] = $v['is_online'] == 1 ? '是' : '否';
            unset($v['job']);

        };

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];
        return result_successed($ret);

    }

    public function courseAdd($params)
    {
        try {

            EhsCourse::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

}