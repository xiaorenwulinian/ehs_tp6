<?php

namespace app\common\service;

use app\common\model\CompanyAreaModel;
use app\common\model\DeviceBraceletMachineModel;
use app\common\model\DeviceCameraModel;
use app\common\model\DeviceIdentifyMachineModel;
use app\common\model\enterprise\DeviceLocationPoint;
use app\common\model\enterprise\DevicePatrolPoint;
use app\common\model\enterprise\DeviceRfid;
use app\common\model\enterprise\DeviceLimitedSpace;
use think\facade\Db;


/**
 *
 * Class DeviceService
 * @package app\common\service
 */
class DeviceService {

    public function rfidIndex($params)
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

        $count = DeviceRfid::where($where)->count();

        $data = DeviceRfid::where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
//            $temp['duty_user_name'] = $v['duty_user']['username'] ?? '';

            unset($temp['is_deleted']);

//            unset($temp['type']);
            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function rfidAdd($params)
    {
        try {

           $has =  DeviceRfid::where('ip',$params['ip'])
               ->where('company_id',$params['company_id'])
               ->count();

           if ($has > 0) {
               throw new \Exception('ip不能重复');
           }

            $params['device_no'] = (new NumberConfigService())->generatorNoRfid($params['company_id']);

            $lineNum = $params['line_num'] ?? 1;
            unset($params['line_num']);
            $data = DeviceRfid::create($params);

            if ($params['type'] == 2) {

                $numArr = range(1,$lineNum);
                $rfidId = $data->id;
                $insert = [];
                foreach ($numArr as $v ) {
                    $temp = [
                        'line_code'  => str_pad($v,2,'0',STR_PAD_LEFT),
                        'company_id' => $params['company_id'],
                        'rfid_id'    => $rfidId,
                        'line_no'    => '',
                    ];
                    $insert[] = $temp;
                }

                Db::name('device_rfid_line')->insertAll($insert);

            }

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function rfidEdit($params)
    {
        try {

            $data = DeviceRfid::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($data->ip != $params['ip']) {
                $has =  DeviceRfid::where('ip',$params['ip'])
                    ->where('id','<>',$params['id'])
                    ->where('company_id',$params['company_id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('ip不能重复');
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function locationPointIndex($params)
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


        $count = DeviceLocationPoint::where($where)->count();


        $data = DeviceLocationPoint::with([
            'locationDevice',
            'companyArea',
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

//        dd($data);
        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['rfid_ip'] = $v['location_device']['ip'] ?? '';
            $temp['rfid_no'] = $v['location_device']['device_no'] ?? '';
            $temp['rfid_name'] = $v['location_device']['name'] ?? '';
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';

            unset($temp['location_device']);
            unset($temp['is_deleted']);
            unset($temp['company_area']);
//            unset($temp['duty_user']);
//            unset($temp['type']);
            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function locationPointAdd($params)
    {
        try {
            $has =  DeviceLocationPoint::where('rfid_id',$params['rfid_id'])
                ->where('company_id',$params['company_id'])
                ->count();

            if ($has > 0) {
                throw new \Exception('设备不能重复');
            }
            $level = CompanyAreaModel::where('id',$params['company_area_id'])->value('cur_level');

            if ($level != 5) {
                throw new \Exception('必须选择一级区域');
            }

            $locationPointNo = (new NumberConfigService)->generatorNoLocationPoint($params['company_id'],$params['company_area_id']);
            $params['device_no'] = $locationPointNo;

            DeviceLocationPoint::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function patrolPointIndex($params)
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

        if (!empty($params['company_area_id'])) {
            $where['company_area_id'] = ['=', $params['company_area_id']];
        }

        $count = DevicePatrolPoint::where($where)->count();

        $data = DevicePatrolPoint::with(['companyArea'])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';

            $temp['device_status_str'] = $temp['device_status'] == 1 ? '正常' : '异常';

            unset($temp['is_deleted']);
            unset($temp['company_area']);
//            unset($temp['type']);
            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function patrolPointAdd($params)
    {
        try {

            $has =  DevicePatrolPoint::where('company_area_id',$params['company_area_id'])
                ->where('name',$params['name'])
                ->count();

            if ($has > 0) {
                throw new \Exception('名称不能重复');
            }

            $level = CompanyAreaModel::where('id',$params['company_area_id'])->value('cur_level');

            if ($level != 5) {
                throw new \Exception('必须选择一级区域');
            }

//            $params['device_no'] = StringLib::random(12);
            DevicePatrolPoint::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function patrolPointEdit($params)
    {
        try {

            $data = DevicePatrolPoint::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($data->name != $params['name']) {
                $has =  DevicePatrolPoint::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->where('company_area_id', $data->company_area_id)
                    ->count();

                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function braceletMachineIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = DeviceBraceletMachineModel::where($where)->count();

        $data = DeviceBraceletMachineModel::where($where)

            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['device_status_str'] = $v['device_status'] == 1 ? '正常' : '异常';
            unset($temp['is_deleted']);

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function braceletMachineAdd($params)
    {
        try {

            $has =  DeviceBraceletMachineModel::where('company_id',$params['company_id'])
                ->where('name',$params['name'])
                ->count();

            if ($has > 0) {
                throw new \Exception('发卡器名称不能重复');
            }


            $params['device_no'] = (new NumberConfigService())->generatorNoBracelet($params['company_id']);
            $params['device_status'] = 1;
            DeviceBraceletMachineModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function braceletMachineEdit($params)
    {
        try {

            $data = DeviceBraceletMachineModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
//            dd($data->company_area_id);

            if ($data->name != $params['name']) {
                $has =  DeviceBraceletMachineModel::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    public function identifyMachineIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = DeviceIdentifyMachineModel::where($where)->count();

        $data = DeviceIdentifyMachineModel::where($where)

            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['device_status_str'] = $v['device_status'] == 1 ?'正常' : '异常';
            unset($temp['is_deleted']);

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function identifyMachineAdd($params)
    {
        try {

            $has =  DeviceIdentifyMachineModel::where('company_id',$params['company_id'])
                ->where('name',$params['name'])
                ->count();

            if ($has > 0) {
                throw new \Exception('读卡器名称重复');
            }

            $params['device_no'] = (new NumberConfigService())->generatorNoIdentify($params['company_id']);
            $params['device_status'] = 1;
            DeviceIdentifyMachineModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function identifyMachineEdit($params)
    {
        try {

            $data = DeviceIdentifyMachineModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
//            dd($data->company_area_id);

            if ($data->name != $params['name']) {
                $has =  DeviceIdentifyMachineModel::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function cameraIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];


        $where['company_id'] = ['=', $params['company_id']];


        if (!empty($params['company_area_id'])) {
            $where['company_area_id'] = ['=', $params['company_area_id']];
        }

        if (!empty($params['department_id'])) {
            $where['department_id'] = ['=', $params['department_id']];
        }


        $count = DeviceCameraModel::where($where)->count();


        $data = DeviceCameraModel::with([
            'companyArea','department'
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';
            $temp['department_name'] = $v['department']['department_name'] ?? '';
            $temp['device_status_str'] = $v['device_status'] == 1 ? '正常' : '异常';

            unset($temp['is_deleted']);
            unset($temp['company_area']);
            unset($temp['department']);

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function cameraAdd($params)
    {
        try {
            $level = CompanyAreaModel::where('id',$params['company_area_id'])->value('cur_level');

            if ($level != 5) {
                throw new \Exception('必须选择一级区域');
            }

            $cameraNo = (new NumberConfigService)->generatorNoCamera($params['company_id'],$params['company_area_id']);
            $params['device_no'] = $cameraNo;

            //添加机器码
            $machine_no = get_rand_str(6, false, true, true);
            while (true) {
                if(Db::name('device_camera')->where('machine_no', $machine_no)->count()){
                    $machine_no = get_rand_str(6, false, true, true);
                }else{
                    break;
                }
            }
            $params['machine_no'] = $machine_no;

            DeviceCameraModel::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function cameraEdit($params)
    {

        try {

            $data = DeviceCameraModel::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($data->ip != $params['ip']) {
                $has =  DeviceCameraModel::where('ip',$params['ip'])
                    ->where('id','<>',$params['id'])
                    ->where('company_id',$params['company_id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('ip不能重复');
                }
                $data->ip = $params['ip'];
            }
            $data->specification = $params['specification'] ??'';

            $data->save();

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();
    }

    public function limitedSpaceIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['company_area_id'])) {
            $where['company_area_id'] = ['=', $params['company_area_id']];
        }

        if (!empty($params['department_id'])) {
            $where['department_id'] = ['=', $params['department_id']];
        }

        if (!empty($params['camera_id'])) {
            $where['camera_id'] = ['=', $params['camera_id']];
        }


        $count = DeviceLimitedSpace::where($where)->count();

        $data = DeviceLimitedSpace::with([
            'companyArea',
            'department',
            'deviceCamera',
            'dutyUser',
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';
            $temp['department_name'] = $v['department']['department_name'] ?? '';
            $temp['ip'] = $v['device_camera']['ip'] ?? '';
            $temp['duty_user_name'] = $v['duty_user']['username'] ?? '';

            unset($temp['is_deleted']);
            unset($temp['company_area']);
            unset($temp['department']);
            unset($temp['device_camera']);
            unset($temp['duty_user']);

            array_push($newData, $temp);
        }

        $ret = [
            'count' => $count,
            'list'  => $newData,
        ];

        return result_successed($ret);

    }

    public function limitedSpaceAdd($params)
    {
        try {

            $has =  DeviceLimitedSpace::where('company_id',$params['company_id'])
                ->where('name',$params['name'])
                ->count();

            if ($has > 0) {
                throw new \Exception('有限空间名称重复');
            }
            DeviceLimitedSpace::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function limitedSpaceEdit($params)
    {
        try {

            $data = DeviceLimitedSpace::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            if ($data->name != $params['name']) {
                $has =  DeviceLimitedSpace::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }

            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


}