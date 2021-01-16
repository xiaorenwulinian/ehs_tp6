<?php

namespace app\common\service;

use app\common\constant\WorkConstant;
use app\common\library\ArrayLib;
use app\common\model\enterprise\WorkHigh;
use think\facade\Db;


/**
 *
 * Class WorkService
 * @package app\common\service
 */
class WorkService {

    public function workCommonIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];



        $tableName = $params['table_name'];
        $count = Db::name($tableName)->where($where)->count();

        $data = Db::name($tableName)
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
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

    public function workCommonShow($params)
    {
        $companyId = $params['company_id'];

        $department = Db::name('department')
            ->where([
                'company_id' => $companyId,
                'is_deleted' => 0,
            ])
            ->field([
                'id',
                'department_name as name',
            ])
            ->select();

        $companyArea = \app\common\model\enterprise\CompanyArea::where('is_deleted', '=', 0)
            ->where('company_id', '=', $companyId)
            ->field(['id', 'name','parent_id'])
            ->select();

        $companyArea = ArrayLib::getTreeMulti($companyArea);

        /*
         * 分级：
         1、作业高度在2米至5米时，称为一级高处作业。
         2、作业高度在5米以上至15米时，称为二级高处作业。
         3、作业高度在15米以上至30米时，称为三级高处作业。
         4、作业高度在30米以上时，称为特级高处作业。

        类别：
        在阵风风力为6级（风速10.8米/秒）及以上情况下进行的强风高处作业；
        在高温或低温环境下进行的异温高处作业；
        在降雪时进行的雪天高处作业；
        在降雨时进行的雨天高处作业；
        在室外完全采用人工照明进行的夜间高处作业；
        在接近或接触带电体条件下进行的带电高处作业；
        在无立足点或无牢靠立足点的条件下进行的悬空高处作业。
        其他
         */

       /* $highLevelData = [
            1 => '作业高度在2米至5米时，称为一级高处作业',
            2 => '作业高度在5米以上至15米时，称为二级高处作业',
            3 => '作业高度在15米以上至30米时，称为三级高处作业',
            4 => '作业高度在30米以上时，称为特级高处作业',
        ];*/
        $highLevelData = [
            1 => '一级高处作业',
            2 => '二级高处作业',
            3 => '三级高处作业',
            4 => '特级高处作业',
        ];
        $highLevel = [];
        foreach ($highLevelData as $k => $v) {
            $highLevel[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        /*$work_typeData = [
            1 => '在阵风风力为6级（风速10.8米/秒）及以上情况下进行的强风高处作业',
            2 => '在高温或低温环境下进行的异温高处作业',
            3 => '在降雪时进行的雪天高处作业',
            4 => '在降雨时进行的雨天高处作业',
            5 => '在室外完全采用人工照明进行的夜间高处作业',
            6 => '在接近或接触带电体条件下进行的带电高处作业',
            7 => '在无立足点或无牢靠立足点的条件下进行的悬空高处作业',
            8 => '其他',
        ];*/
        $work_high_typeData = [
            1 => '一般高处作业',
            2 => '特殊高处作业',

        ];
        $work_high_type = [];
        foreach ($work_high_typeData as $k => $v) {
            $work_high_type[] = [
              'id'    => $k,
              'name'  => $v,
            ];
        }

        $work_cate_data = WorkConstant::WORK_TYPE_ARR;
        $work_cate = [];
        $cate_id = $params['cate_id'] ?? 0;
        foreach ($work_cate_data as $k => $v) {
            if ($k === $cate_id) {
                continue;
            }
            $work_cate[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }

        $fireLevelData = [
            1 => '一级动火作业',
            2 => '二级动火作业',
            3 => '三级动火作业',
        ];
        $fireLevel = [];
        foreach ($fireLevelData as $k => $v) {
            $fireLevel[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $electricLevelData = [
            1 => '一级',
            2 => '二级',
            3 => '三级',
        ];
        $electricLevel = [];
        foreach ($electricLevelData as $k => $v) {
            $electricLevel[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $electricDeviceData = [
            1 => '用电设备一',
            2 => '用电设备二',
            3 => '用电设备三',
        ];
        $electricDevice = [];
        foreach ($electricDeviceData as $k => $v) {
            $electricDevice[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $limitSpareTypeData = [
            1 => '地上',
            2 => '地下',
            3 => '设施设备',
        ];
        $limitSpareType = [];
        foreach ($limitSpareTypeData as $k => $v) {
            $limitSpareType[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $slingLevelData = [
            1 => '一级:>100吨',
            2 => '二级:40-100吨',
            3 => '三级:40吨以下',
        ];
        $slingLevel = [];
        foreach ($slingLevelData as $k => $v) {
            $slingLevel[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $slingDeviceData = [
            1 => '吊装设备一',
            2 => '吊装设备二',
            3 => '吊装设备三',
        ];
        $slingDevice = [];
        foreach ($slingDeviceData as $k => $v) {
            $slingDevice[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $openCircuiTypeData = [
            1 => '车辆禁行',
            2 => '全部禁行',
        ];
        $openCircuiType = [];
        foreach ($openCircuiTypeData as $k => $v) {
            $openCircuiType[] = [
                'id'  => $k,
                'name' => $v,
            ];
        }

        $ret = [
            'department' => $department,
            'company_area' => $companyArea,
            'high_level' => $highLevel,
            'work_high_type' => $work_high_type,
            'fireLevel' => $fireLevel,
            'electricLevel' => $electricLevel,
            'electricDevice' => $electricDevice,
            'limitSpareType' => $limitSpareType,
            'slingLevel' => $slingLevel,
            'openCircuiType' => $openCircuiType,
            'work_cate' => $work_cate,
        ];

        return result_successed($ret);

    }

    public function highIndex($params)
    {

        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];


        $count = WorkHigh::where($where)->count();

        $data = WorkHigh::with([
//            'companyArea',
//            'leaderJob',
        ])->where($where)
//            ->field('sort,is_deleted',true)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;

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

    public function workCommonAdd($params)
    {
        Db::startTrans();


        try {

            $work_user_id = $params['work_user_id'] ?? '';
            $duty_department_id  = $params['duty_department_id'] ?? 0;
            $monitor_user_id     = $params['monitor_user_id'] ?? 0;
            $confirm_user_id     = $params['confirm_user_id'] ?? 0;
            $charge_user_id      = $params['charge_user_id'] ?? 0;

            $out_work_user_id        = $params['out_work_user_id'] ?? '';
            $out_duty_department_id  = $params['out_duty_department_id'] ?? 0;
            $out_monitor_user_id     = $params['out_monitor_user_id'] ?? 0;
            $out_confirm_user_id     = $params['out_confirm_user_id'] ?? 0;
            $out_charge_user_id      = $params['out_charge_user_id'] ?? 0;

            $out = [
                '外包现场作业人' => $out_work_user_id,
                '外包单位' => $out_duty_department_id,
                '外包监护人' => $out_monitor_user_id,
                '外包确认人' => $out_confirm_user_id,
                '外包负责人' => $out_charge_user_id,
            ];

            $my = [
//                'out_work_user_id' => $out_work_user_id,
                '现场作业人' => $work_user_id,
                '责任部门' => $duty_department_id,
                '监护人' => $monitor_user_id,
                '确认人' => $confirm_user_id,
                '负责人' => $charge_user_id,
            ];

            $cooper = [
                '现场作业人' => $work_user_id,
                '责任部门' => $duty_department_id,
                '监护人' => $monitor_user_id,
                '确认人' => $confirm_user_id,
                '负责人' => $charge_user_id,
                '外包现场作业人' => $out_work_user_id,
                '外包单位' => $out_duty_department_id,
                '外包监护人' => $out_monitor_user_id,
                '外包确认人' => $out_confirm_user_id,
                '外包负责人' => $out_charge_user_id,
            ];
            // 1. 外包， 2本方， 3协作
            if (1 == $params['operate_type']) {
                foreach ($out as $k => $v) {
                    if (empty($v)) {
                        throw new \Exception("外包字段:{$k}必传");
                    }
                }
//                if (
//                    empty($out_work_user_id)
//                    || empty($out_duty_department_id)
//                    || empty($out_monitor_user_id)
//                    || empty($out_confirm_user_id)
//                    || empty($out_charge_user_id)
//                ) {
//                    throw new \Exception('外包所有字段必传');
//                }
            } elseif (2 == $params['operate_type']) {
                foreach ($my as $k => $v) {
                    if (empty($v)) {
                        throw new \Exception("本方字段:{$k}必传");
                    }
                }

            } else {
                foreach ($cooper as $k => $v) {
                    if (empty($v)) {
                        throw new \Exception("协作字段:{$k}必传");
                    }
                }
            }

            $form_type_id = $params['work_link_type'];
            if (!array_key_exists($form_type_id, WorkConstant::WORK_TYPE_ARR)) {
                throw new \Exception("类型不匹配！");
            }
            $curTime = date("Y-m-d H:i:s");

            $work_total_insert = [
                'company_id'          => $params['company_id'],
                'apply_department_id' => $params['apply_department_id'],
                'company_area_id'     => $params['company_area_id'],
//                'work_type_id'        => $params['work_type_id'],
//                'work_level_id'       => $params['work_level_id'],
//                'work_address'        => $params['work_address'],
                'start_time'          => $params['start_time'],
                'end_time'            => $params['end_time'],
                'other_work'          => $params['other_work'] ?? '',
                'operate_type'        => $params['operate_type'],

                'work_user_id'        => $params['work_user_id'] ?? '',
                'duty_department_id'  => $params['duty_department_id'] ?? 0,
                'monitor_user_id'     => $params['monitor_user_id'] ?? 0,
                'confirm_user_id'     => $params['confirm_user_id'] ?? 0,
                'charge_user_id'      => $params['charge_user_id'] ?? 0,

                'out_work_user_id'        => $params['out_work_user_id'] ?? '',
                'out_duty_department_id'  => $params['out_duty_department_id'] ?? 0,
                'out_monitor_user_id'     => $params['out_monitor_user_id'] ?? 0,
                'out_confirm_user_id'     => $params['out_confirm_user_id'] ?? 0,
                'out_charge_user_id'      => $params['out_charge_user_id'] ?? 0,

                'work_link_type'      => $form_type_id,
                'addtime'             => $curTime,


            ];
            $specialInsert = [];
            $tableName = '';
            switch ($params['work_link_type']) {
                case WorkConstant::WORK_HIGH:
                    $specialInsert = [
                        'work_type_id'        => $params['work_type_id'],
                        'work_level_id'       => $params['work_level_id'],
                        'work_address'        => $params['work_address'],
                    ];
                    $tableName = 'work_high';
                    break;
                case WorkConstant::WORK_FIRE:
                    $specialInsert = [
//                        'work_type_id'        => $params['work_type_id'],
                        'work_level_id'       => $params['work_level_id'],
                        'work_address'        => $params['work_address'],
                    ];
                    $tableName = 'work_fire';
                    break;
                case WorkConstant::WORK_DIRT:
                    $specialInsert = [
                        'work_depth'         => $params['work_depth'],
                        'work_acreage'       => $params['work_acreage'],
                        'work_address'        => $params['work_address'],
                    ];
                    $tableName = 'work_dirt';
                    break;
                case WorkConstant::WORK_ELECTRIC:
                    $specialInsert = [
                        'work_device_id'         => $params['work_device_id'],
                        'work_level_id'       => $params['work_level_id'],
                    ];
                    $tableName = 'work_electric';
                    break;
                case WorkConstant::WORK_LIMIT_SPARE:
                    $specialInsert = [
                        'work_address'        => $params['work_address'],
                        'work_type_id'       => $params['work_type_id'],
                    ];
                    $tableName = 'work_limit_spare';
                    break;
            }
            if (empty($tableName)) {
                throw new \Exception("table not exist！");
            }

//            dd($work_total_insert);
            $work_total_id =  Db::name('work_total')->insertGetId($work_total_insert);
//            dump($work_total_id);
            $work_common_insert = [
                'company_id'          => $params['company_id'],
                'apply_department_id' => $params['apply_department_id'],
                'company_area_id'     => $params['company_area_id'],

                'start_time'          => $params['start_time'],
                'end_time'            => $params['end_time'],
                'other_work'          => $params['other_work'] ?? '',
                'operate_type'        => $params['operate_type'],

                'work_user_id'        => $params['work_user_id'] ?? '',
                'duty_department_id'  => $params['duty_department_id'] ?? 0,
                'monitor_user_id'     => $params['monitor_user_id'] ?? 0,
                'confirm_user_id'     => $params['confirm_user_id'] ?? 0,
                'charge_user_id'      => $params['charge_user_id'] ?? 0,

                'out_work_user_id'        => $params['out_work_user_id'] ?? '',
                'out_duty_department_id'  => $params['out_duty_department_id'] ?? 0,
                'out_monitor_user_id'     => $params['out_monitor_user_id'] ?? 0,
                'out_confirm_user_id'     => $params['out_confirm_user_id'] ?? 0,
                'out_charge_user_id'      => $params['out_charge_user_id'] ?? 0,

                'work_content'        => $params['work_content'] ?? '',
                'attachments'         => $params['attachments'] ?? '',
                'photo_files'         => $params['photo_files'] ?? '',
                'work_total_id'       => $work_total_id,
                'addtime'             => $curTime,
            ];

            $work_combine_insert = $specialInsert + $work_common_insert;
            Db::name($tableName)->insert($work_combine_insert);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();
    }


    public function workCommonEdit($params)
    {
        try {
            $specialUpdate = [];
            $tableName = '';
            switch ($params['work_link_type']) {
                case WorkConstant::WORK_HIGH:
                    $specialUpdate = [
                        'work_type_id'        => $params['work_type_id'],
                        'work_level_id'       => $params['work_level_id'],
                        'other_work'          => $params['other_work'],
                    ];
                    $tableName = 'work_high';
                    break;
                case WorkConstant::WORK_FIRE:
                    $specialUpdate = [
                        'work_type_id'        => $params['work_type_id'],
                        'work_level_id'       => $params['work_level_id'],
                        'other_work'          => $params['other_work'],
                    ];
                    $tableName = 'work_fire';
                    break;
            }
            if (empty($tableName)) {
                throw new \Exception("table not exist！");
            }

            $data = Db::name($tableName)
                ->where('id', $params['id'])
                ->find();

            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data['company_id'] != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }



            $commonUpdate = [
                'apply_department_id'   => $params['apply_department_id'],
                'company_area_id'       => $params['company_area_id'],
                'work_address'          => $params['work_address'],
                'start_time'            => $params['start_time'],
                'end_time'              => $params['end_time'],
                'operate_type'          => $params['operate_type'],

                'work_user_id'          => $params['work_user_id'] ?? '',
                'duty_department_id'    => $params['duty_department_id'] ?? 0,
                'monitor_user_id'       => $params['monitor_user_id'] ?? 0,
                'confirm_user_id'       => $params['confirm_user_id'] ?? 0,
                'charge_user_id'        => $params['charge_user_id'] ?? 0,

                'out_work_user_id'      => $params['out_work_user_id'] ?? '',
                'out_duty_department_id' => $params['out_duty_department_id'] ?? 0,
                'out_monitor_user_id'   => $params['out_monitor_user_id'] ?? 0,
                'out_confirm_user_id'   => $params['out_confirm_user_id'] ?? 0,
                'out_charge_user_id'    => $params['out_charge_user_id'] ?? 0,

                'work_content'          => $params['work_content'] ?? '',
                'attachments'           => $params['attachments'] ?? '',
                'photo_files'           => $params['photo_files'] ?? '',

            ];

            $combineUpdate = $specialUpdate + $commonUpdate;

            Db::name($tableName)
                ->where('id', $params['id'])
                ->update($combineUpdate);


            $totalUpdate = [];
            $totalUpdate['apply_department_id'] = $params['apply_department_id'];
            $totalUpdate['company_area_id'] = $params['company_area_id'];
            $totalUpdate['work_type_id'] = $params['work_type_id'];
            $totalUpdate['work_level_id'] = $params['work_level_id'];
            $totalUpdate['work_address'] = $params['work_address'];
            $totalUpdate['start_time'] = $params['start_time'];
            $totalUpdate['end_time'] = $params['end_time'];
            $totalUpdate['other_work'] = $params['other_work'];
            $totalUpdate['operate_type'] = $params['operate_type'];

            $totalUpdate['work_user_id'] = $params['work_user_id'] ?? '';
            $totalUpdate['duty_department_id'] = $params['duty_department_id'] ?? 0;
            $totalUpdate['monitor_user_id'] = $params['monitor_user_id'] ?? 0;
            $totalUpdate['confirm_user_id'] = $params['confirm_user_id'] ?? 0;
            $totalUpdate['charge_user_id'] = $params['charge_user_id'] ?? 0;

            $totalUpdate['out_work_user_id'] = $params['out_work_user_id'] ?? '';
            $totalUpdate['out_duty_department_id'] = $params['out_duty_department_id'] ?? 0;
            $totalUpdate['out_monitor_user_id'] = $params['out_monitor_user_id'] ?? 0;
            $totalUpdate['out_confirm_user_id'] = $params['out_confirm_user_id'] ?? 0;
            $totalUpdate['out_charge_user_id'] = $params['out_charge_user_id'] ?? 0;

            Db::name('work_total')
                ->where('id', $data['work_total_id'])
                ->update($totalUpdate);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    public function highAdd($params)
    {
        Db::startTrans();
        try {

            $curTime = date("Y-m-d H:i:s");

            $work_total_insert = [
                'company_id'          => $params['company_id'],
                'apply_department_id' => $params['apply_department_id'],
                'company_area_id'     => $params['company_area_id'],
                'work_type_id'        => $params['work_type_id'],
                'work_level_id'       => $params['work_level_id'],
                'work_address'        => $params['work_address'],
                'start_time'          => $params['start_time'],
                'end_time'            => $params['end_time'],
                'other_work'          => $params['other_work'] ?? '',
                'operate_type'        => $params['operate_type'],
                'status'              => $params['status'] ?? 1,
                'work_user_id'        => $params['work_user_id'] ?? '',
                'duty_department_id'  => $params['duty_department_id'],
                'monitor_user_id'     => $params['monitor_user_id'],
                'confirm_user_id'     => $params['confirm_user_id'],
                'charge_user_id'      => $params['charge_user_id'],
                'work_link_type'      => WorkConstant::WORK_HIGH,
                'addtime'             => $curTime,


            ];
//            dd($work_total_insert);
            $work_total_id =  Db::name('work_total')->insertGetId($work_total_insert);
//            dump($work_total_id);
            $work_high_insert = [
                'company_id'          => $params['company_id'],
                'apply_department_id' => $params['apply_department_id'],
                'company_area_id'     => $params['company_area_id'],
                'work_type_id'        => $params['work_type_id'],
                'work_level_id'       => $params['work_level_id'],
                'work_address'        => $params['work_address'],
                'start_time'          => $params['start_time'],
                'end_time'            => $params['end_time'],
                'other_work'          => $params['other_work'] ?? '',
                'operate_type'        => $params['operate_type'],
                'status'              => $params['status'] ?? 1,
                'work_user_id'        => $params['work_user_id'],
                'duty_department_id'  => $params['duty_department_id'],
                'monitor_user_id'     => $params['monitor_user_id'],
                'confirm_user_id'     => $params['confirm_user_id'],
                'charge_user_id'      => $params['charge_user_id'],
                'work_content'        => $params['work_content'] ?? '',
                'attachments'         => $params['attachments'] ?? '',
                'photo_files'         => $params['photo_files'] ?? '',
                'work_total_id'       => $work_total_id,
                'addtime'             => $curTime,
            ];
            Db::name('work_high')->insert($work_high_insert);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();
    }

    public function highEdit($params)
    {
        try {

            $data = WorkHigh::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            $data->apply_department_id = $params['apply_department_id'];
            $data->company_area_id = $params['company_area_id'];
            $data->work_type_id = $params['work_type_id'];
            $data->work_level_id = $params['work_level_id'];
            $data->work_address = $params['work_address'];
            $data->start_time = $params['start_time'];
            $data->end_time = $params['end_time'];
            $data->other_work = $params['other_work'];
            $data->operate_type = $params['operate_type'];
            $data->work_user_id = $params['work_user_id'];
            $data->duty_department_id = $params['duty_department_id'];
            $data->monitor_user_id = $params['monitor_user_id'];
            $data->confirm_user_id = $params['confirm_user_id'];
            $data->charge_user_id = $params['charge_user_id'];
            $data->work_content = $params['work_content'];
            $data->attachments = $params['attachments'];
            $data->photo_files = $params['photo_files'];

            $data->save();

            $totalUpdate = [];
            $totalUpdate['apply_department_id'] = $params['apply_department_id'];
            $totalUpdate['company_area_id'] = $params['company_area_id'];
            $totalUpdate['work_type_id'] = $params['work_type_id'];
            $totalUpdate['work_level_id'] = $params['work_level_id'];
            $totalUpdate['work_address'] = $params['work_address'];
            $totalUpdate['start_time'] = $params['start_time'];
            $totalUpdate['end_time'] = $params['end_time'];
            $totalUpdate['other_work'] = $params['other_work'];
            $totalUpdate['operate_type'] = $params['operate_type'];

            $totalUpdate['work_user_id'] = $params['work_user_id'];
            $totalUpdate['duty_department_id'] = $params['duty_department_id'];
            $totalUpdate['monitor_user_id'] = $params['monitor_user_id'];
            $totalUpdate['confirm_user_id'] = $params['confirm_user_id'];
            $totalUpdate['charge_user_id'] = $params['charge_user_id'];

            Db::name('work_total')
                ->where('id', $data['work_total_id'])
                   ->update($totalUpdate);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function userSelect($params)
    {
        $companyId = $params['company_id'];

        $user = Db::name('user')
            ->where([
                'company_id' => $companyId,
                'is_deleted' => 0,
            ])
            ->field([
                'id',
                'username as name',
            ])
            ->select();

        $ret = [
            'list' => $user,
        ];

        return result_successed($ret);

    }



}