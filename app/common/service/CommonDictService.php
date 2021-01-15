<?php

namespace app\common\service;

use think\facade\Db;


/**
 *
 * 通用字典 service
 * Class CommonDictService
 * @package app\common\service
 */
class CommonDictService {

    public function index($params)
    {

        $where = [];

        $where['company_id'] = ['=', $params['company_id']];

        $tableName = $params['table_name'];

        $count =  Db::name($tableName)->where($where)->count();

        $data = Db::name($tableName)->where($where)->select();

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }


    public function edit($params)
    {
        try {


            $companyId = $params['company_id'];
            $tableName = $params['table_name'];
            $content   = $params['content'];

            $data = Db::name($tableName)
                ->where('company_id', $companyId)
                ->column('*','id');

            $hasIdArr = array_keys($data);

            $insert = [];
            foreach ($content as $v) {
                $name = !empty($v['name']) ? $v['name'] : '';
                if (!$name) {
                    continue;
                }

                $sort       = !empty($v['sort']) ? $v['sort'] : 0;
                $isDefault  = !empty($v['is_default']) ? $v['is_default'] : 2;
                $isShow     = !empty($v['is_show']) ? $v['is_show'] : 2;

                if ($v['id'] == 0) {
                    $temp = [
                        'company_id' => $companyId,
                        'name'       => $name,
                        'is_default' => $isDefault,
                        'is_show'    => $isShow,
                        'sort'       => $sort,
                    ];
                    array_push($insert, $temp);
                } else {

                    if (!in_array($v['id'], $hasIdArr)) {
                        continue;
                    }
                    $curData = $data[$v['id']];

                    if ($curData['name'] == $name
                        && $curData['is_default'] == $isDefault
                        && $curData['is_show'] == $isShow
                        && $curData['sort'] == $sort
                    ) {
                        continue;
                    }

                    Db::name($tableName)
                        ->where('company_id', $companyId)
                        ->where('id', $v['id'])
                        ->update([
                            'name'       => $name,
                            'is_default' => $isDefault,
                            'is_show'    => $isShow,
                            'sort'       => $sort,
                        ]);
                }
            }

            if (!empty($insert)) {
                Db::name($tableName)->insertAll($insert);
            }

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }



}