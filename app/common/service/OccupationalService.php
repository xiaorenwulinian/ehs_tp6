<?php

namespace app\common\service;

use app\common\library\StringLib;
use app\common\model\CompanyAreaModel;
use app\common\model\FacilityModel;
use app\common\model\JobModel;
use app\common\model\OcTabooModel;
use app\common\model\OcTestPlanModel;
use think\facade\Db;


/**
 *
 * Class OccupationalService
 * @package app\common\service
 */
class OccupationalService {


    public function harmFactorIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
//        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = OcHarmFactorModel::where($where)->count();

        $data = OcHarmFactorModel::where($where)
            ->limit($offset, $pageSize)
//            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    private function generatorFacilityNo()
    {

        return StringLib::random(12);
    }

    public function add($params)
    {
        Db::startTrans();
        try {

            $level =  CompanyAreaModel::where('id',$params['company_area_id'])->value('cur_level');

            if ($level != 5) {
                throw new \Exception('必须选择一级区域');
            }


            $insert = [
                'company_id'         => $params['company_id'],
                'company_area_id'    => $params['company_area_id'],
                'type'               => $params['type'],
                'name'               => $params['name'],
                'start_time'         => $params['start_time'],
                'status'             => $params['status'],
                'maintain_frequent'  => $params['maintain_frequent'],
                'department_id'      => $params['department_id'],
                'leader_job_id'      => $params['leader_job_id'],
                'worker_job_id'      => $params['worker_job_id'],
                'check_time_ids'     => $params['check_time_ids'],
                'rfid_ids'           => $params['rfid_ids'],
                'sop_ids'            => $params['sop_ids'] ?? '',
                'accept_data'        => $params['accept_data'] ?? '',
                'instruction'        => $params['instruction'] ?? '',
                'facility_no'        => $this->generatorFacilityNo(),
            ];

            $facility = FacilityModel::create($insert);

            $facilityId = $facility->id;

            $damageContent = $params['damage_goods_content'] ?? [];
            $damage = [];
            if (!empty($damageContent)) {
                foreach ($damageContent as $v) {
                    if (empty($v['name'])
                        || empty($v['specification'])
                        || empty($v['num'])
                        || empty($v['min_stock'])
                    ) {
                        continue;
                    }

                    $temp = [
                        'name'          => $v['name'],
                        'specification' => $v['specification'],
                        'num'           => $v['num'],
                        'min_stock'     => $v['min_stock'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,
                    ];
                    array_push($damage, $temp);
                }
            }
            if (!empty($damage)) {
                Db::name('easy_damage_goods')->insertAll($damage);

            }

            $dangerSourceContent = $params['danger_source_content'] ?? [];
            $dangerSource = [];
            if (!empty($dangerSourceContent)) {
                foreach ($dangerSourceContent as $v) {
                    if (empty($v['name'])
                        || empty($v['project'])
                    ) {
                        continue;
                    }

                    $temp = [
                        'name'          => $v['name'],
                        'project'       => $v['project'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,
                    ];
                    array_push($dangerSource, $temp);
                }
            }

            if (!empty($dangerSource)) {
                Db::name('danger_source')->insertAll($dangerSource);
            }

            $factorContent = $params['environment_factor_content'] ?? [];
            $envFactor = [];
            if (!empty($factorContent)) {
                foreach ($factorContent as $v) {
                    if (empty($v['name'])
                        || empty($v['project'])
                    ) {
                        continue;
                    }

                    $temp = [
                        'name'          => $v['name'],
                        'project'       => $v['project'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,
                    ];
                    array_push($envFactor, $temp);
                }
            }

            if (!empty($envFactor)) {
                Db::name('environment_factor')->insertAll($envFactor);
            }


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function edit($params)
    {

        Db::startTrans();
        try {
            $data = FacilityModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            $level =  CompanyAreaModel::where('id',$params['company_area_id'])->value('cur_level');

            if ($level != 5) {
                throw new \Exception('必须选择一级区域');
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            $data->name              = $params['name'];
            $data->status            = $params['status'];
            $data->maintain_frequent = $params['maintain_frequent'];
            $data->sop_ids           = $params['sop_ids'];

            $data->accept_data       = $params['accept_data'];
            $data->instruction       = $params['instruction'];

            $data->save($params);

            $facilityId = $data->id;

            $damageContent = $params['damage_goods_content'] ?? [];

            $this->easyDamageGoods($params, $facilityId, $damageContent);

            $dangerSourceContent = $params['danger_source_content'] ?? [];
            $this->dangerSource($params, $facilityId, $dangerSourceContent);

            $factorContent = $params['environment_factor_content'] ?? [];
            $this->environmentFactor($params, $facilityId, $factorContent);



            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    /**
     * 易损件
     * @param $params
     * @param $facilityId
     * @param $damageContent
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function easyDamageGoods($params, $facilityId, $content)
    {
        if (!empty($content)) {

            $data = Db::name('easy_damage_goods')
                ->where('facility_id', $facilityId)
                ->column('*','id');

            $hasIdArr = array_keys($data);

            $insert = [];
            $reqIdArr = [];

            foreach ($content as $v) {
                if (empty($v['name'])
                    || empty($v['specification'])
                    || empty($v['num'])
                    || empty($v['min_stock'])
                ) {
                    continue;
                }

                if ($v['id'] == 0) {
                    $temp = [
                        'name'          => $v['name'],
                        'specification' => $v['specification'],
                        'num'           => $v['num'],
                        'min_stock'     => $v['min_stock'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,

                    ];
                    array_push($insert, $temp);
                } else {
                    if (!in_array($v['id'], $hasIdArr)) {
                        continue;
                    }

                    $reqIdArr[] = $v['id'];

                    $curData = $data[$v['id']];

                    if ($curData['name'] == $v['name']
                        && $curData['specification'] == $v['specification']
                        && $curData['num']           == $v['num']
                        && $curData['min_stock']     == $v['min_stock']
                    ) {
                        continue;
                    }

                    Db::name('easy_damage_goods')
                        ->where('id',$v['id'])
                        ->update([
                            'name'          => $v['name'],
                            'specification' => $v['specification'],
                            'num'           => $v['num'],
                            'min_stock'     => $v['min_stock'],
                        ]);
                }

            }

            $deleteIdArr = array_diff($hasIdArr, $reqIdArr);


            if (!empty($deleteIdArr)) {
                Db::name('easy_damage_goods')
                    ->where('id','in', $deleteIdArr)
                    ->where('facility_id',$facilityId)
                    ->delete();
            }

            if (!empty($insert)) {
                Db::name('easy_damage_goods')->insertAll($insert);
            }

        } else {
            Db::name('easy_damage_goods')
                ->where('facility_id',$facilityId)
                ->delete();

        }

        return true;

    }


    /**
     * 影响因素
     * @param $params
     * @param $facilityId
     * @param $content
     * @return bool
     */
    private function environmentFactor($params, $facilityId, $content)
    {
        if (!empty($content)) {

            $data = Db::name('environment_factor')
                ->where('facility_id', $facilityId)
                ->column('*','id');

            $hasIdArr = array_keys($data);

            $insert = [];
            $reqIdArr = [];

            foreach ($content as $v) {
                if (empty($v['name'])
                    || empty($v['project'])
                ) {
                    continue;
                }

                if ($v['id'] == 0) {
                    $temp = [
                        'name'          => $v['name'],
                        'project'       => $v['project'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,

                    ];
                    array_push($insert, $temp);
                } else {
                    if (!in_array($v['id'], $hasIdArr)) {
                        continue;
                    }

                    $reqIdArr[] = $v['id'];

                    $curData = $data[$v['id']];

                    if ($curData['name'] == $v['name']
                        && $curData['project'] == $v['project']
                    ) {
                        continue;
                    }

                    Db::name('environment_factor')
                        ->where('id',$v['id'])
                        ->update([
                            'name'          => $v['name'],
                            'project'       => $v['project'],
                        ]);
                }

            }

            $deleteIdArr = array_diff($hasIdArr, $reqIdArr);

            if (!empty($deleteIdArr)) {
                Db::name('danger_source')
                    ->where('id','in', $deleteIdArr)
                    ->where('facility_id',$facilityId)
                    ->delete();
            }

            if (!empty($insert)) {
                Db::name('environment_factor')->insertAll($insert);
            }

        } else {
            Db::name('environment_factor')
                ->where('facility_id',$facilityId)
                ->delete();

        }

        return true;

    }


    /**
     * 危险源
     */
    private function dangerSource($params, $facilityId, $content)
    {
        if (!empty($content)) {

            $data = Db::name('danger_source')
                ->where('facility_id', $facilityId)
                ->column('*','id');

            $hasIdArr = array_keys($data);

            $insert = [];
            $reqIdArr = [];

            foreach ($content as $v) {
                if (empty($v['name'])
                    || empty($v['project'])
                ) {
                    continue;
                }

                if ($v['id'] == 0) {
                    $temp = [
                        'name'          => $v['name'],
                        'project'       => $v['project'],
                        'company_id'    => $params['company_id'],
                        'facility_id'   => $facilityId,

                    ];
                    array_push($insert, $temp);
                } else {
                    if (!in_array($v['id'], $hasIdArr)) {
                        continue;
                    }

                    $reqIdArr[] = $v['id'];

                    $curData = $data[$v['id']];

                    if ($curData['name'] == $v['name']
                        && $curData['project'] == $v['project']
                    ) {
                        continue;
                    }

                    Db::name('danger_source')
                        ->where('id',$v['id'])
                        ->update([
                            'name'          => $v['name'],
                            'project'       => $v['project'],
                        ]);
                }

            }

            $deleteIdArr = array_diff($hasIdArr, $reqIdArr);

            if (!empty($deleteIdArr)) {
                Db::name('danger_source')
                    ->where('id','in', $deleteIdArr)
                    ->where('facility_id',$facilityId)
                    ->delete();
            }

            if (!empty($insert)) {
                Db::name('danger_source')->insertAll($insert);
            }

        } else {
            Db::name('danger_source')
                ->where('facility_id',$facilityId)
                ->delete();

        }

        return true;

    }

    public function delete($id)
    {
        $data = FacilityModel::get($id);
        if (!$data) {
            return api_failed("数据不存在");
        }
        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return  api_failed($e->getMessage());
        }

        return true;

    }

    public function detail($id)
    {
        $data = FacilityModel::get($id);
        if (!$data) {
            return api_failed("数据不存在");
        }
        try {
            $sopIds = $data->sop_ids;
            if (!empty($sopIds)) {
                $sopData = Db::name('sop')
                    ->whereIn('id', explode(',', $sopIds))
                    ->select();
            } else {
                $sopData = [];
            }

            $dangerSource =  Db::name('danger_source')
                ->where('facility_id', $id)
                ->select();

            $envFactor =  Db::name('environment_factor')
                ->where('facility_id', $id)
                ->select();

            $damageGoods = Db::name('easy_damage_goods')
                ->where('facility_id', $id)
                ->select();

        } catch (\Exception $e) {
            return  api_failed($e->getMessage());
        }

        $info = [
            'sop_data' => $sopData,
            'danger_source_data' => $dangerSource,
            'environment_factor_data' => $envFactor,
            'damage_goods_data' => $damageGoods,
            'info' => $data,
//            'sop_data' => $sopData,
        ];

        return result_successed($info);

    }

    /*
     *职业禁忌症
     */
    public function ocTabooIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $count = OcTabooModel::where($where)->count();

        $data = OcTabooModel::where($where)
            ->limit($offset, $pageSize)
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function testPlanIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
//        $where['company_id'] = ['=', $params['company_id']];

        $count = OcTestPlanModel::where($where)->count();

        $data = OcTestPlanModel::with([
            'user',
            'department',
            'job'
        ])
            ->where($where)
            ->limit($offset, $pageSize)
//            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {

            $temp = $v;
            $temp['department_name'] = $v['department']['department_name'] ?? '';
            $temp['username'] = $v['user']['username'] ?? '';
            $temp['job_name'] = $v['job']['job_name'] ?? '';
            if ($v['is_job'] == 1) {
                $temp['is_job_str'] = '岗前';
                $t_name = 'before_target_item';
            } elseif ($v['is_job'] == 2) {
                $temp['is_job_str'] = '在岗';
                $t_name = 'cur_target_item';
            } else {
                $temp['is_job_str'] = '离岗';
                $t_name = 'leave_target_item';

            }
            if (!empty($v['test_item'])) {



                $nameArr = Db::name('oc_harm_factor')
                    ->whereIn('id', explode(',', $v['test_item']))
                    ->column($t_name,'id');
                $temp['test_item_name'] = implode(',', $nameArr);
            } else {
                $temp['test_item_name'] = '';
            }



            unset($temp['job']);
            unset($temp['user']);
            unset($temp['department']);

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function testPlanAdd($params)
    {

        try {
//            $has =  OcTestPlan::where('company_id',$params['company_id'])
//                ->where('test_item',$params['test_item'])
//                ->count();
//
//            if ($has > 0) {
//                throw new \Exception('体检项目不能为空');
//            }
            $params['test_item'] = JobModel::where('id',$params['job_id'])
                ->value('harm_factor_id');
            OcTestPlanModel::create($params);
//
        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function testPlanEdit($params)
    {

        try {
            $data = OcTestPlanModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            $data->user_id              = $params['user_id'];
            $data->department_id            = $params['department_id'];
            $data->job_id = $params['job_id'];
            $data->before_time           = $params['before_time'];
            $data->next_time       = $params['next_time'];
            $test_item = JobModel::where('id',$params['job_id'])
                ->value('harm_factor_id');
            $data->test_item       = $test_item;
            $data->is_job       = $params['is_job'];

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }



}