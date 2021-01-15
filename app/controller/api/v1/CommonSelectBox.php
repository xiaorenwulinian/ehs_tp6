<?php


namespace app\controller\api\v1;


use app\common\constant\CommonConstant;
use app\common\constant\ObjectConstant;
use app\common\constant\WorkConstant;
use app\common\library\ArrayLib;
use app\common\model\enterprise\DeviceRfid;
use app\common\service\JwtService;
use app\controller\api\ApiBase;
use think\facade\Db;

/**
 * @descption 公用的下拉框
 * Class CommonSelectBox
 * @package app\api\controller\v1
 */
class CommonSelectBox extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    protected $noCompanyIdArr = [
        'riskColor',
        'safetySignsColor',
        'ppeType',
        'hazardousType'
    ];

    private $companyId = null;

    public function _initialize()
    {
//        parent::_initialize();

        $method = $this->request->action();
        $noCompanyIdArr = $this->noCompanyIdArr;

        foreach($noCompanyIdArr as &$v) {
            $v = strtolower($v);
        }
//        dd($method, $noCompanyIdArr);
        if (!in_array($method, $noCompanyIdArr)) {
            $this->companyId = JwtService::getInstance()->getCompanyId();
            /*$this->companyId = input('company_id/d', 0);
            if (empty($this->companyId)) {
                return api_failed("公司id必须");
            }*/
        }


    }

    /**
     * 公司区域
     */
    public function companyArea()
    {

        $data = \app\common\model\enterprise\CompanyArea::where('is_deleted', '=', 0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'name as name'])
            ->select();

        return api_successed(compact('data'));
    }

    /**
     * 公司区域
     */
    public function companyAreaMulti()
    {

        $data = \app\common\model\enterprise\CompanyArea::where('is_deleted', '=', 0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id', 'name','parent_id'])
            ->select();

        $list = ArrayLib::getTreeMulti($data);

        return api_successed(compact('list'));
    }

    /**
     * 岗位
     */
    public function job()
    {
        $data = \app\common\model\enterprise\Job::where('is_deleted', '=', 0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'job_name as name'])
            ->select();

        return api_successed(compact('data'));
    }

    /**
     * 安全检查频次
     */
    public function checkRate()
    {
        $data = Db::name('check_rate')->where('is_deleted', '=', 0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'check_rate_name as name'])
            ->select();

        return api_successed(compact('data'));
    }


    /**
     * 安全检查频次
     */
    public function user()
    {
        $where = [];
        $where['company_id'] = ['=', $this->companyId];
        $name = \input('keyword/s','');
        if (!empty($name)) {
            $where['username|nickname|pinyin|pinyin_short'] = ['like',"%{$name}%"];
        }
        $data = Db::name('user')
            ->field([
                'id',
                'username as name',
//                'nickname',
//                'pinyin',
//                'pinyin_short',
            ])
            ->where($where)
            ->select();



        return api_successed(compact('data'));
    }

    /**
     * 公司-设备-监控、感应、定位
     */
    public function companyDeviceMonitor()
    {
        $list = Db::name('company_device_monitor')
            ->where('is_deleted', '=', 0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'device_name as name'])
            ->select();

        return api_successed(compact('list'));
    }

    /**
     * EHS点检时机
     */
    public function ehsPointCheckTime()
    {
        $list = Db::name('ehs_point_check_time')
//            ->where('is_deleted','=',0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'point_name as name'])
            ->select();

        return api_successed(compact('list'));
    }

    /**
     * EHS点检时机
     */
    public function ehsCheckStand()
    {
        $list = Db::name('ehs_check_standard')
//            ->where('is_deleted','=',0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'standard_name as name'])
            ->select();

        return api_successed(compact('list'));
    }


    /**
     * EHS点检时机
     */
    public function department()
    {

        $data = Db::name('department')
//            ->where('is_deleted','=',0)
            ->where('company_id', '=', $this->companyId)
            ->where('is_deleted', 0)
            ->field(['id as id', 'parent_id', 'department_name as name'])
            ->order('parent_id', 'asc')
            ->select();

        $data = collection($data)->toArray();

        $list = \app\common\model\enterprise\Department::getTreeMulti($data);

        return api_successed(['list' => $list]);
    }


    /*
     * 设备状态
     */
    public function deviceState()
    {
        $data = CommonConstant::DEVICE_MONITOR_STATE_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }

        return api_successed(['list' => $list]);
    }

    /*
     * 设备类型
     *
     */
    public function deviceType()
    {
        $data = CommonConstant::COMPANY_DEVICE_MONITOR_TYPE;
        $list = [];
        foreach ($data as $k => $v) {
            $temp = [
                'id'   => $k,
                'name' => $v,
            ];
            $list[] = $temp;
        }

        return api_successed(['list' => $list]);
    }


    /*
     * 风险等级
     *
     */
    public function riskLevel()
    {
        $list = [
            [
                'id'   => 1,
                'name' => 'A',
            ],
            [
                'id'   => 2,
                'name' => 'B',
            ],
            [
                'id'   => 3,
                'name' => 'C',
            ],
            [
                'id'   => 4,
                'name' => 'D',
            ],

        ];

        return api_successed(['list' => $list]);
    }



    public function rfidDevice()
    {
        $data = DeviceRfid::SCENE_ARR;
        $scene = request()->param('scene');

        $where = [];
        $where['is_deleted'] = ['=', 0];

        if (array_key_exists($scene, $data)) {
            $where['scene'] = ['=', $scene];
        }
        $where['company_id'] = ['=', $this->companyId];


        $list = Db::name('device_rfid')
            ->where($where)
            ->field(['id as id', 'name as name'])
            ->select();

        return api_successed(compact('list'));
    }

    /*
     * 事故类型
     */
    public function accidentType()
    {
        $data = CommonConstant::ACCIDENT_TYPE_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /*
    * 不符合类型
    */
    public function nonconformingType()
    {
        $data = CommonConstant::NONCONFORMING_TYPE_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }


    /*
     * 培训类型
     */
    public function trainType()
    {
        $data = CommonConstant::TRAIN_TYPE_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /*
     * 学习内容类别
     */
    public function studyContent()
    {
        $data = CommonConstant::STUDY_CONTENT_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /*
     * 事故等级
     */
    public function accidentLevel()
    {
        $data = CommonConstant::ACCIDENT_LEVEL_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /*
     * 危废类型
     */
    public function hazardousType()
    {
        $data = ObjectConstant::HAZARDOUS_OBJECT;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'  => $k,
                'no'  =>  $v['no'],
                'name'=>  $v['name'],
            ];
        }
        return json(result_successed(compact('list')));
    }

    public function camera()
    {
        $list = Db::name('device_camera')
//            ->where('is_deleted','=',0)
            ->where('company_id', '=', $this->companyId)
            ->field(['id as id', 'ip as name'])
            ->select();

        return json(result_successed(compact('list')));
    }

    /*
    * PPE
    */
    public function ppeType()
    {
        $data = ObjectConstant::PPE_OBJECT;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'  => $k,
                'name'=> $v['name'],
            ];
        }
        return json(result_successed(compact('list')));
    }

    /**
     * 职业病类型
     */
    public function occupationalDiseaseKind()
    {
        $data = ObjectConstant::OD_KIND_OBJECT;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /**
     * 职业病
     */
    public function occupationalDisease()
    {
        $type  = input('cate_id/d',0);
        $list = Db::name('occupational_disease')
            ->where('cate_id', '=', $type)
            ->field(['id', 'name'])
            ->select();

        return json(result_successed(compact('list')));

    }

    /*
     * 职业病有害因素
     */
    public function ocHarmFactor()
    {
        $list = Db::name('oc_harm_factor')
            ->field(['id as id', 'name as name'])
            ->select();

        return api_successed(compact('list'));
    }


    /**
     * 特种作业类型
     */
    public function specialWork()
    {
        $data = ObjectConstant::SPECIAL_WORK_OBJECT;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v['name'],
            ];
        }
        return json(result_successed(compact('list')));
    }

    /*
     * 区域权限配置
     */
    public function areaPermissionConfig()
    {
        $data = CommonConstant::AREA_PERMISSION_CONFIG;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }
        return json(result_successed(compact('list')));
    }

    /**
     * 风险色标
     * @return \think\response\Json
     */
    public function riskColor()
    {
        $list = [
            [
                'id' => 1,
                'name' => '红',
                'color' => '#ff0000',
            ],
            [
                'id' => 2,
                'name' => '橙',
                'color' => '#ff9900',
            ],
            [
                'id' => 3,
                'name' => '黄',
                'color' => '#ffff00',
            ],
            [
                'id' => 4,
                'name' => '蓝',
                'color' => '#0099ff',
            ],
        ];
        return json(result_successed(compact('list')));

    }
    /*
     * 安全标识色标
     */
    public function safetySignsColor()
    {
        $list = [
            [
                'id'   => '1',
                'name' => '红',
                'color'=> '#ff0000'
            ],
            [
                'id'   => '2',
                'name' => '黄',
                'color'=> '#ffff00'
            ],
            [
                'id'   => '3',
                'name' => '蓝',
                'color'=> '#0099ff'
            ],
            [
                'id'   => '4',
                'name' => '绿',
                'color'=> '#00FF00'
            ],
        ];
        return json(result_successed(compact('list')));
    }

    /*
     * 特种作业类型
     */
    public function workType()
    {
        $data = WorkConstant::WORK_TYPE_ARR;
        $list = [];
        foreach ($data as $k => $v) {
            $list[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }

        return api_successed(['list' => $list]);
    }
}
