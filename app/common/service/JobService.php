<?php

namespace app\common\service;


use app\common\constant\CommonConstant;
use app\common\constant\ObjectConstant;
use app\common\model\enterprise\EhsCourse;
use app\common\model\enterprise\EmergencyPlan;
use app\common\model\enterprise\Facility;
use app\common\model\enterprise\Job;
use app\common\model\enterprise\JobQualify;
use app\common\model\enterprise\JobSetting;
use app\common\model\enterprise\Ppe;
use app\common\model\enterprise\JobPpe;
use app\common\model\enterprise\JobEmergency;
use app\common\model\enterprise\JobCourse;
use app\common\traits\SingletonTrait;
use think\facade\Db;


/**
 *
 * Class JobService
 * @package app\common\service
 */
class JobService {

    use SingletonTrait;


    public function index($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['job_name'])) {
            $where['job_name'] = ['like', "%{$params['job_name']}%"];
        }

        $count = Job::where($where)->count();

        $data = Job::with([
            'companyArea',
            'department',
        ])
        ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();
        $newData = [];
        $specialWorkArr = ObjectConstant::SPECIAL_WORK_OBJECT;

        $harmAll = Db::name('oc_harm_factor')->column('name','id');

        foreach ($data as $v) {

            $v['special_work_name'] = $specialWorkArr[$v['special_work_id']]['name'] ?? '';

            $v['forbid_area_name'] = $v['company_area']['name'] ?? '';
            $v['department_name'] = $v['department']['department_name'] ?? '';

            if (!empty($v['harm_factor_id'])) {

                $factorIdArr = explode(',', $v['harm_factor_id']);
                $factorNameArr = [];

                foreach ($factorIdArr as $factorId) {
                    if (!array_key_exists($factorId, $harmAll) ) {
                        continue;
                    }
                    $factorNameArr[] = $harmAll[$factorId];
                }
                if (!empty($factorNameArr)) {
                    $v['harm_factor_str'] = implode(',', $factorNameArr);
                }
            } else {
                $v['harm_factor_str'] = '';
            }


            unset($v['is_deleted']);
            unset($v['company_area']);
            unset($v['department']);

            array_push($newData,$v);

        }


        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }

    public function addShow($companyId)
    {
        $permissionData = CommonConstant::AREA_PERMISSION_CONFIG;
        $permission = [];
        foreach ($permissionData as $k => $v) {
            $permission[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }

        $harm = Db::name('oc_harm_factor')
            ->where('company_id', $companyId)
            ->field(['id', 'name'])
            ->select();

        $specialData = ObjectConstant::SPECIAL_WORK_OBJECT;
        $specialWork = [];
        foreach ($specialData as $k => $v) {
            $specialWork[] = [
                'id'   => $k,
                'name' => $v['name'],
            ];
        }
        $ret = [
            'area_permission'  => $permission,
            'harm_factor' => $harm,
            'special_work' => $specialWork,
        ];

        return result_successed($ret);
    }

    public function add($params)
    {
        try {

            $job_no = (new NumberConfigService())->generatorNoJob($params['company_id']);

            $insert = [
                'job_no'         => $job_no,
                'company_id'     => $params['company_id'],
                'job_name'       => $params['job_name'],
                'department_id'  => $params['department_id'],
                'auth_config'    => $params['auth_config'] ?? '',
                'forbid_area_id' => $params['forbid_area_id'] ?? 0,
                'special_work_id' => $params['special_work_id'] ?? 0,
                'company_area_id' => $params['company_area_id'] ?? 0,
                'job_role_label_id' => $params['job_role_label_id'] ?? 0,
                'harm_factor_id' => $params['harm_factor_id'] ?? '',
            ];
            $job = Job::create($insert);
            $jobId = $job->id;

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
                        'facility_id'   => $jobId,
                        'type'          => 2,
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
                        'facility_id'   => $jobId,
                        'type'          => 2,
                    ];
                    array_push($envFactor, $temp);
                }
            }

            if (!empty($envFactor)) {
                Db::name('environment_factor')->insertAll($envFactor);
            }

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    public function edit($params)
    {
        try {

            $job = Job::find($params['id']);
            if (!$job) {
                throw new \Exception("未发现该职位");
            }

            if ($job->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }

            $job->forbid_area_id  = $params['forbid_area_id'] ?? 0;
            $job->auth_config     = $params['auth_config'] ?? '';
            $job->special_work_id = $params['special_work_id'] ?? 0;
            $job->company_area_id = $params['company_area_id'] ?? 0;
            $job->job_role_label_id = $params['job_role_label_id'] ?? 0;
            $job->harm_factor_id  = $params['harm_factor_id'] ?? '';
            $job->save();

            $dangerSourceContent = $params['danger_source_content'] ?? [];
            (new FacilityService())->dangerSource($params, $job->id, $dangerSourceContent, 2);

            $factorContent = $params['environment_factor_content'] ?? [];
            (new FacilityService())->environmentFactor($params, $job->id, $factorContent);


        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function delete($id)
    {
        $data = Job::find($id);
        if (!$data) {
            return api_failed("数据不存在");
        }

        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return  result_failed($e->getMessage());
        }

        return result_successed();

    }


    public function qualifyIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['qualify_name'])) {
            $where['qualify_name'] = ['like', "%{$params['qualify_name']}%"];
        }

        $count = JobQualify::where($where)->count();

        $data = JobQualify::where($where)
            ->field([
                'job_qualify_id',
                'qualify_name',
                'desc',
            ])
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();


        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }


    public function jobSettingIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['company_area_id'])) {
            $where['company_area_id'] = ['=', $params['company_area_id']];
        }

        if (!empty($params['camera_id'])) {
            $where['camera_id'] = ['=', $params['camera_id']];
        }

        if (!empty($params['job_id'])) {
            $where['job_id'] = ['=', $params['job_id']];
        }

        if (!empty($params['rfid_id'])) {
            $where['rfid_id'] = ['=', $params['rfid_id']];
        }

        $count = JobSetting::where($where)->count();

        $data = JobSetting::with([
            'companyArea',
            'job',
            'rfidDevice',
            'deviceCamera'
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $newData = [];

        foreach ($data as $v) {
            $temp = $v;
            $temp['company_area_name'] = $v['company_area']['name'] ?? '';
            $temp['job_name'] = $v['job']['job_name'] ?? '';
            $temp['ip'] = $v['device_camera']['ip'] ?? '';
            $temp['name'] = $v['rfid_device']['name'] ?? '';
            $temp['status_str'] = $v['status'] == 1 ? '正常' : '异常';


            unset($temp['is_deleted']);
            unset($temp['company_area']);
            unset($temp['job']);
            unset($temp['device_camera']);
            unset($temp['rfid_device']);

            array_push($newData, $temp);
        }


        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return result_successed($ret);

    }


    public function jobSettingAdd($params)
    {
        try {
            $settingNo = (new NumberConfigService)->generatorNoJobSetting($params['company_id'], $params['company_area_id']);
            $params['setting_no'] = $settingNo;
            JobSetting::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();
    }

    public function ppeIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = Ppe::where($where)->count();

        $data = Ppe::where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $data = collection($data)->toArray();
//        dd($data);

        $ppeTypeArr = ObjectConstant::PPE_OBJECT;
        foreach ($data as &$v) {
            if (array_key_exists($v['ppe_type_id'], $ppeTypeArr)) {
                $v['ppe_type_name'] = $ppeTypeArr[$v['ppe_type_id']]['name'] ?? '';
            } else {
                $v['ppe_type_name'] = '';
            }

            $v['firm_rate_type_str'] = $v['firm_rate_type'] == 1 ? '年' : '月';
            $v['attribute_str'] = $v['attribute'] == 1 ? '一般PPE' : '特种PPE';
//                dd($v['job_name']);
//            unset($v['job']);

        }
        $ret = [
            'count' => $count,
            'list'  => $data,
        ];
        return result_successed($ret);

    }

    public function ppeAdd($params)
    {
        try {

           Ppe::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function ppeEdit($params)
    {
        try {
//dd(1);
            $data = Ppe::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
            if ($data->name != $params['name']) {
                $has =  Ppe::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->count();

                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }
//            $data->job_id = $params['job_id'];
            $data->ppe_type_id = $params['ppe_type_id'];
            $data->name = $params['name'];
            $data->type = $params['type'];
            $data->spec = $params['spec'];
            $data->brand = $params['brand'];
            $data->firm_rate = $params['firm_rate'];
            $data->attribute = $params['attribute'];
            $data->firm_rate_type = $params['firm_rate_type'];

            $data->save();

//            $data->save($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function emergencyPlanIndex($params)
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
        if (!empty($params['department_id'])) {
            $where['department_id'] = ['=', $params['department_id']];
        }
        $count = EmergencyPlan::where($where)->count();

        $data = EmergencyPlan::with([
            'job',
            'department'
        ])
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();

        $data = collection($data)->toArray();
//        dd($data);

        foreach ($data as &$v) {
            $v['job_name'] = $v['job']['job_name'] ?? '';
            $v['department_name'] = $v['department']['department_name'] ?? '';
            $v['evaluate_type_str'] = $v['evaluate_type'] == 1 ? '书面' : '演习';
            unset($v['job']);
            unset($v['department']);

        };
        $ret = [
            'count' => $count,
            'list'  => $data,
        ];
        return result_successed($ret);

    }

    public function emergencyPlanAdd($params)
    {
        try {

            $data = EmergencyPlan::create($params);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();

    }

    public function emergencyPlanEdit($params)
    {
        try {
//            dump($params);
            $data = EmergencyPlan::find($params['id']);

            if (!$data) {

                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
            if ($data->name != $params['name']) {
                $has =  EmergencyPlan::where('name',$params['name'])
                    ->where('id','<>',$params['id'])
                    ->count();
                if ($has > 0) {
                    throw new \Exception('name不能重复');
                }
            }

            $data->name = $params['name'];
            $data->excess_plan = $params['excess_plan'];
            $data->evaluate_type = $params['evaluate_type'];

            $data->save();

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();
    }



    public function dangerSourceIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['facility_id'])) {
            $where['facility_id'] = ['=', $params['facility_id']];

        }

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = Db::name('danger_source')
            ->where($where)
            ->count();

        /*
         序号、区域/活动、危险源、岗位、部门、控制措施
         */
        $data = Db::name('danger_source')
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();
        $list = [];
        foreach ($data as $v) {
            $temp = [
                'id' => $v['id'],
                'danger_name' => $v['name'],
                'project' => $v['project'],
                'company_id' => $v['company_id'],
                'link_id' => $v['facility_id'],
                'type' => $v['type'],
                'type_str' => $v['type'] === 1 ? '设施' : '岗位',
                'link_name' => '',
                'company_area_id' => '',
                'company_area_name' => '',
                'department_id' => '',
                'department_name' => '',


            ];
            if ($v['type'] === 1) {
                $t = Facility::with([
                    'companyArea',
                    'department',
                ])
                    ->where('id', $v['facility_id'])
                    ->find()
                    ->toArray();
                $temp['link_facility_name'] = $t['name'] ?? '';
                $temp['link_job_name'] =  '';
                $temp['company_area_id'] = $t['company_area']['id'] ?? '';
                $temp['company_area_name'] = $t['company_area']['name'] ?? '';

                $temp['department_id'] = $t['department']['id'] ?? '';
                $temp['department_name'] = $t['department']['department_name'] ?? '';

            } else {
                $t = Job::with([
                    'department',
                ])
                    ->where('id', $v['facility_id'])
                    ->find()
                    ->toArray();

                $temp['link_job_name'] = $t['job_name'] ?? '';
                $temp['link_facility_name'] =  '';

                $temp['department_id'] = $t['department']['id'] ?? '';
                $temp['department_name'] = $t['department']['department_name'] ?? '';

            }

            $list[] = $temp;

        }

        $ret = [
            'count' => $count,
            'list'  => $list,
        ];

        return result_successed($ret);

    }


    public function environmentFactorIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['facility_id'])) {
            $where['facility_id'] = ['=', $params['facility_id']];
        }

        if (!empty($params['name'])) {
            $where['name'] = ['like', "%{$params['name']}%"];
        }

        $count = Db::name('environment_factor')
            ->where($where)
            ->count();

        /*
         序号、区域/活动、危险源、岗位、部门、控制措施
         */
        $data = Db::name('environment_factor')
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();
        $list = [];
        foreach ($data as $v) {
            $temp = [
                'id' => $v['id'],
                'danger_name' => $v['name'],
                'project' => $v['project'],
                'company_id' => $v['company_id'],
                'link_id' => $v['facility_id'],
                'type' => $v['type'],
                'type_str' => $v['type'] === 1 ? '设施' : '岗位',
                'job_name' => '',
                'company_area_id' => '',
                'company_area_name' => '',
                'department_id' => '',
                'department_name' => '',

            ];
            if ($v['type'] === 1) {
                $t = Facility::with([
                    'companyArea',
                    'department',
                ])
                    ->where('id', $v['facility_id'])
                    ->find()
                    ->toArray();

                $temp['link_facility_name'] = $t['name'] ?? '';
                $temp['link_job_name'] =  '';
                $temp['company_area_id'] = $t['company_area']['id'] ?? '';
                $temp['company_area_name'] = $t['company_area']['name'] ?? '';

                $temp['department_id'] = $t['department']['id'] ?? '';
                $temp['department_name'] = $t['department']['department_name'] ?? '';

            } else {
                $t = Job::with([
                    'department',
                ])
                    ->where('id', $v['facility_id'])
                    ->find()
                    ->toArray();

                $temp['link_job_name'] = $t['job_name'] ?? '';
                $temp['link_facility_name'] =  '';

                $temp['department_id'] = $t['department']['id'] ?? '';
                $temp['department_name'] = $t['department']['department_name'] ?? '';

            }

            $list[] = $temp;

        }

        $ret = [
            'count' => $count,
            'list'  => $list,
        ];

        return result_successed($ret);

    }

    public function roleLabelIndex($params)
    {
        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

        $where['company_id'] = ['=', $params['company_id']];



        $count = Db::name('job_role_label')
            ->where($where)
            ->count();


        $data = Db::name('job_role_label')
            ->where($where)
//            ->limit($offset, $pageSize)
            ->order('id','desc')
            ->select();
        $list = [];
        foreach ($data as $v) {
            $temp = $v;
            $list[] = $temp;

        }

        $ret = [
            'count' => $count,
            'list'  => $list,
        ];

        return result_successed($ret);

    }

    public function detail($id)
    {
        $data = Job::find($id);
        if (!$data) {
            return result_failed('数据不存在');
        }


        $authConfig = [];
        if (!empty($data->auth_config)) {
            $acIdArr = explode(',', $data->auth_config);
            $all = CommonConstant::AREA_PERMISSION_CONFIG;

            foreach ($all as $key => $v) {
                if (in_array($key, $acIdArr)) {
                    $authConfig[] = [
                        'id' => $key,
                        'name' => $v,
                    ];
                }
            }
        }
        if (empty($data->harm_factor_id)) {
            $oc_harm_factor_ids = [];
        } else {
            $oc_harm_factor_ids = explode(',', $data->harm_factor_id);
        }

        $oc_arr = [];
        $taboo_arr = [];
        $factor_name_arr = [];

        // oc_harm_factor_base_id
        $taboo = ObjectConstant::TABOO_TYPE;
        foreach ($oc_harm_factor_ids as $oc_harm_factor_id) {
            $oc_harm_factor = Db::name('oc_harm_factor')
                ->where('id', $oc_harm_factor_id)
                ->find();
            $factor_name_arr[] = $oc_harm_factor['name'];
//            ->value('before_target_item');
            $before_target_item = $oc_harm_factor['before_target_item'];
            $oc_harm_factor_base_id = $oc_harm_factor['oc_harm_factor_base_id'];
            $oc_taboo = $taboo[$oc_harm_factor_base_id]['pre_work'] ?? '';

            $oc_arr[] = $before_target_item;
            $taboo_arr[] = $oc_taboo;
        }

        $danger_source = Db::name('danger_source')
            ->where([
                'type' => 2,
                'facility_id' => $data['id'],
            ])
            ->select();

        $environment_factor = Db::name('environment_factor')
            ->where([
                'type' => 2,
                'facility_id' => $data['id'],
            ])
            ->select();

        $ret = [
            'auth_Config' => $authConfig,
            'attr' => [
                'factor_name' => $factor_name_arr,
                'taboo' => $taboo_arr, // 职业病禁忌
                'oc'    => $oc_arr, // 职业病防治
                'special_work_name'  => ObjectConstant::SPECIAL_WORK_OBJECT[$data['special_work_id']]['name'] ?? '', // 职业病防治
            ],
            'danger_source' => $danger_source,
            'environment_factor' => $environment_factor,

        ];
        return result_successed($ret);
    }

    public function bindPpeEdit($params)
    {
        $data = JobPpe::find($params['id']);
        if (!$data) {
            return result_failed("数据不存在");
        }

        if ($data['company_id'] != $params['company_id']) {
            return result_failed("非法攻击");
        }

        $data->ppe_receive_rate = $params['ppe_receive_rate'];
        $data->save();

        return result_successed();
    }

    public function bindPpeDelete($params)
    {
        $data = JobPpe::find($params['id']);
        if (!$data) {
            return result_failed("数据不存在");
        }

        if ($data['company_id'] != $params['company_id']) {
            return result_failed("非法攻击");
        }

        JobPpe::where('id', $params['id'])->delete();
        return result_successed();
    }

    public function bindPpeAdd($params)
    {
        $job_id = $params['job_id'];
        $company_id = $params['company_id'];
        $ppeContent = $params['ppeContent'] ?? [];

        if (empty($ppeContent)) {
            return result_failed('添加内容不能为空');
        }

        foreach ($ppeContent as $v) {
            if (empty($v['link_id']) || empty($v['ppe_receive_rate'])) {
                continue;
            }
            if ($v['id'] == 0) {
                $has = Db::name('job_ppe')
                    ->where('job_id',$job_id)
//                    ->where('ppe_receive_rate',$v['ppe_receive_rate'])
                    ->where('link_id',$v['link_id'])
                    ->count();
                if ($has < 1) {
                    Db::name('job_ppe')->insert([
                        'link_id' => $v['link_id'],
                        'ppe_receive_rate' => $v['ppe_receive_rate'],
                        'job_id' => $job_id,
                        'company_id' => $company_id,
                    ]);
                }
                continue;
            }

        }

        return result_successed();


    }

    public function bindPpe($params)
    {
        $job_id = $params['job_id'];
        $company_id = $params['company_id'];
        $ppeContent = $params['ppeContent'] ?? [];
        /*
         ppeContent : [
            {
                'id' : 0,
                'link_id' :1,
                'ppe_receive_rate':1,
            }
        ]
         【
             [
                'id' =>0,
                'link_id' =>1,
                'ppe_receive_rate' =>1,
            ]
             [
                'id' =>1,
                'link_id' =>2,
                'ppe_receive_rate' =>1,
            ]
        】
         */
        if (!empty($ppeContent)) {
            $hasPpeData = Db::name('job_ppe')
                ->where('job_id',$job_id)
                ->column('*','id');
            $hasIdArr = array_keys($hasPpeData);

            $deleteArr = [];

            $receiptIdArr = [];
            foreach ($ppeContent as $v) {
               if ($v['id'] == 0) {
                   continue;
               }
                $receiptIdArr[] = $v['id'];
            }
            // 1,2,3 req
            // 2,3,5 database
            $diffDeleteId = array_diff($hasIdArr, $hasIdArr);
            if (!empty($deleteArr)) {
                Db::name('job_ppe')
                    ->whereIn('id',$diffDeleteId)
                    ->delete();
            }

            foreach ($ppeContent as $v) {
                if (empty($v['link_id']) || empty($v['ppe_receive_rate'])) {
                    continue;
                }
                if ($v['id'] == 0) {
                    continue;
                }
                Db::name('job_ppe')
                    ->where('job_id',$job_id)
                    ->where('id',$v['id'])
                    ->update([
                        'link_id' => $v['link_id'],
                        'ppe_receive_rate' => $v['ppe_receive_rate'],
                    ]);
            }

            foreach ($ppeContent as $v) {
                if (empty($v['link_id']) || empty($v['ppe_receive_rate'])) {
                    continue;
                }
                if ($v['id'] == 0) {
                    $has = Db::name('job_ppe')
                        ->where('job_id',$job_id)
                        ->where('ppe_receive_rate',$v['ppe_receive_rate'])
                        ->where('link_id',$v['link_id'])
                        ->count();
                    if ($has < 1) {
                        Db::name('job_ppe')->insert([
                            'link_id' => $v['link_id'],
                            'ppe_receive_rate' => $v['ppe_receive_rate'],
                            'job_id' => $job_id,
                            'company_id' => $company_id,
                        ]);
                    }
                    continue;
                }

            }


        } else {
            Db::name('environment_factor')
                ->where('company_id', $company_id)
                ->where('job_id',$job_id)
                ->delete();
        }

        // 1-2，2-2，3-1


        return result_successed();

    }


    public function bind($params)
    {
//        dd($params);
        $tableName = $params['identify'];
        $job_id = $params['job_id'];
        $link_ids = $params['link_ids'] ?? '';
        $company_id = $params['company_id'];

        if (!empty($link_ids)) {
            $hasLinkIdArr = Db::name($tableName)
                ->where('job_id', $job_id)
                ->column('link_id','id');

//            dd($hasLinkIdArr);
            $request_link_id_arr = explode(',', $link_ids);
            // 1,2, 3
            // 2,3,4

            $insertIdArr = array_diff($request_link_id_arr, $hasLinkIdArr);
            $deleteIdArr = array_diff($hasLinkIdArr,$request_link_id_arr);

            if (!empty($insertIdArr)) {
                foreach ($insertIdArr as $link_id) {
                    Db::name($tableName)
                        ->insert([
                            'job_id' => $job_id,
                            'company_id' => $company_id,
                            'link_id' => $link_id,
                    ]);
                }
            }

            if (!empty($deleteIdArr)) {
                Db::name($tableName)
                    ->where('job_id', $job_id)
                    ->whereIn('link_id', $deleteIdArr)
                    ->delete();
            }

        } else {
            Db::name($tableName)
                ->where('job_id', $job_id)
                ->delete();
        }

        return result_successed();

    }

    public function bindPpeDetail($jobId)
    {

        $linkIdArr = JobPpe::where(['job_id'=> $jobId])->column('link_id');

//        dd($linkIdArr);
        $ppe = Db::name('ppe')
//            ->field([
//                'id',
//                'attribute',
//                'firm_rate_type',
//            ])
            ->whereIn('id', $linkIdArr)
            ->select()->toArray();

        $ppeTypeArr = ObjectConstant::PPE_OBJECT;

        $list = [];
        foreach ($ppe as $v) {
            $temp = $v;

            if (array_key_exists($v['ppe_type_id'], $ppeTypeArr)) {
                $temp['ppe_type_str'] = $ppeTypeArr[$v['ppe_type_id']]['name'] ?? '';
            } else {
                $temp['ppe_type_str'] = '';
            }
            $temp['attribute_str'] = $v['attribute'] == 1 ? '一般劳动防护用品' : '特种劳动防护用品';
            $temp['firm_rate_type_str'] = $v['firm_rate_type'] == 1 ? '年' : '月';
//            $arr = [
//                1 => 'nian',
//                2 => 'yue',
//                3 => 'ri',
//            ];
//            $temp['firm_rate_type_str'] = $arr[$v['firm_rate_type']] ?? '';

//            unset($temp['attribute']);
//            unset($temp['firm_rate_type']);
            array_push($list, $temp);
        }
        $ret = [
            'list' => $list,
//            'list' => $list,
        ];
        return result_successed($ret);
    }

    public function bindCourseDetail($jobId)
    {

        $linkIdArr = JobCourse::where(['job_id'=> $jobId])->column('link_id');

        $course = EhsCourse::with([
                'job'
            ])
            ->whereIn('id', $linkIdArr)
            ->select();
//        dd($ppe);

        $list = [];

        foreach ($course as $v) {
//            $temp = $v;
            $v['job_name'] = $v['job']['job_name'] ?? '';
            $v['type_str'] = $v['type'] == 1 ? '通用课程' : '专业课程';
            $v['is_online_str'] = $v['is_online'] == 1 ? '是' : '否';
            unset($v['job']);
            array_push($list, $v);
        }

        $ret = [
//            'ehs_course' => $course,
            'list' => $list,
        ];
        return result_successed($ret);
    }

    public function bindEmergencyDetail($jobId)
    {

        $linkIdArr = JobEmergency::where(['job_id'=> $jobId])->column('link_id');

        $emergency = Db::name('emergency_plan')
            ->with([
                'job',
                'department',
            ])
            ->whereIn('id', $linkIdArr)
            ->select();

        $emergency = EmergencyPlan::with([
                'department',
                'job',
            ])
            ->whereIn('id', $linkIdArr)
            ->select();

        $list = [];

        foreach ($emergency as $v) {
            $temp = $v;
            $temp['job_name'] = $v['job']['job_name'] ?? '';
            $temp['department_name'] = $v['department']['department_name'] ?? '';
            $temp['evaluate_type_str'] = $v['evaluate_type'] == 1 ? '书面' : '演习';
            unset($temp['department']);
            unset($temp['job']);
            array_push($list, $temp);
        }
        $ret = [
//            'emergency_plan' => $emergency,
            'list' => $list,
        ];
        return result_successed($ret);
    }



}