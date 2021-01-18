<?php

namespace app\common\service;

use app\common\constant\CommonConstant;
use app\common\library\StringLib;
use app\common\model\CompanyAreaModel;
use app\common\model\enterprise\Facility;
use app\common\traits\SingletonTrait;
use think\facade\Db;


/**
 *
 * Class FacilityService
 * @package app\common\service
 */
class FacilityService {

    use SingletonTrait;


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

        $count = Facility::where($where)->count();

        $data = Facility::with([
            'companyArea',
            'leaderJob',
        ])->where($where)
//            ->field('sort,is_deleted',true)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['start_time'] = empty($v['start_time']) ? '' : date('Y-m-d', strtotime($v['start_time']));
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';
            $temp['leader_job_name'] = $v['leader_job']['job_name'] ?? '';
            $temp['worker_job_name'] = $v['worker_job']['job_name'] ?? '';
            $temp['department_name'] = $v['department']['department_name'] ?? '';
//            $temp['company_area_name'] = $v['company_area']['name'] ?? '';

            $temp['status_str'] = CommonConstant::DEVICE_MONITOR_STATE_ARR[$v['status']] ?? '';


            if (!empty($v['rfid_ids'])) {
                $idArr = explode(',', $v['rfid_ids']);
                $str = Db::name('device_rfid')
                    ->whereIn('id', $idArr)
                    ->column('name');
                $temp['rfid_ids_str'] = implode(',', $str);
            } else {
                $temp['rfid_ids_str'] = '';
            }

            if (!empty($v['check_time_ids'])) {
                $tempIdArr = [];
                $checkTimeIdArr = explode(',', $v['check_time_ids']);
                foreach ($checkTimeIdArr as $cti) {
                    if (!array_key_exists($cti, Facility::CHECK_TIME_ARR)) {
                        continue;
                    }
                    $tempIdArr[] = Facility::CHECK_TIME_ARR[$cti];
                }
                $temp['check_time_ids_str'] = implode(',',$tempIdArr);
            } else {
                $temp['check_time_ids_str'] = '';
            }

            unset($temp['sort']);
            unset($temp['is_deleted']);
            unset($temp['duty_user']);
            unset($temp['company_area']);
            unset($temp['leader_job']);
            unset($temp['worker_job']);
            unset($temp['department']);
//            unset($temp['type']);
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

           $facility = Facility::create($insert);

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
                        'type'          => 1,
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
                        'type'          => 1,
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
            $data = Facility::get($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

//            $level =  CompanyArea::where('id',$params['company_area_id'])->value('cur_level');
//
//            if ($level != 5) {
//                throw new \Exception('必须选择一级区域');
//            }

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
    public function environmentFactor($params, $facilityId, $content, $type = 1)
    {
        if (!empty($content)) {

            $data = Db::name('environment_factor')
                ->where('facility_id', $facilityId)
                ->where('type', $type)
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
                        'type'          => $type,

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
                        ->where('type', $type)
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
                    ->where('type', $type)
                    ->where('facility_id',$facilityId)
                    ->delete();
            }

            if (!empty($insert)) {
                Db::name('environment_factor')->insertAll($insert);
            }

        } else {
            Db::name('environment_factor')
                ->where('type', $type)
                ->where('facility_id',$facilityId)
                ->delete();

        }

        return true;

    }


    /**
     * 危险源
     * @param $params
     * @param $facilityId
     * @param $content
     * @param int $type 危险源类型，1设施，2岗位
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function dangerSource($params, $facilityId, $content, $type = 1)
    {
        if (!empty($content)) {

            $data = Db::name('danger_source')
                ->where('facility_id', $facilityId)
                ->where('type', $type)
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
                        'type'          => $type,

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
                        ->where('type', $type)
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
                    ->where('type', $type)
                    ->where('facility_id',$facilityId)
                    ->delete();
            }

            if (!empty($insert)) {
                Db::name('danger_source')->insertAll($insert);
            }

        } else {
            Db::name('danger_source')
                ->where('type', $type)
                ->where('facility_id',$facilityId)
                ->delete();

        }

        return true;

    }

    public function delete($id)
    {
        $data = Facility::find($id);
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
        $data = Facility::find($id);
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


}