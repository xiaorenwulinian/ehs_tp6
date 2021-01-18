<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\ObjectConstant;
use app\common\service\JobService;
use app\common\service\JwtService;
use app\common\validate\CommonValidate;
use app\common\validate\EmerPlanValidate;
use app\common\validate\JobValidate;
use app\common\validate\PpeValidate;

/**
 * 公司区域
 */
class Job extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * @var JobService
     */
    private $jobService = null;


    public function _initialize()
    {
        parent::_initialize();

        $this->jobService = new JobService();


    }

    /**
     * 查询
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $params = $this->request->param();

//        api_validate(JobValidate::class, 'index', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->index($params);
        return json($ret);

    }

    public function addShow()
    {
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $ret = (new JobService())->addShow($companyId);

        return json($ret);
    }

    /**
     * 岗位添加
     * @return \think\response\Json
     */
    public function add()
    {
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([

            'department_id',
            'job_name',
            'forbid_area_id',
            'special_work_id',
            'auth_config',
            'harm_factor_id',
            'company_area_id',
            'job_role_label_id',

            'danger_source_content',
            'environment_factor_content',
        ]);


        api_validate(JobValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->add($params);
        return json($ret);

     }



    /**
     * 修改
     * @author lcl
     * @date 2020/11/24
     * @return \think\response\Json
     */
    public function edit()
    {

        $params = $this->request->only([
            'id',
            'department_id',
            'job_name',
            'forbid_area_id',
            'special_work_id',
            'auth_config',
            'harm_factor_id',
            'company_area_id',
            'job_role_label_id',


            'danger_source_content',
            'environment_factor_content',

        ]);

        api_validate(JobValidate::class, 'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->edit($params);
        return json($ret);

    }

    /**
     * 删除
     * @author lcl
     * @date 2020/11/23
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delete()
    {
        $id = input('id/d',0);

        return json($this->jobService->delete($id));

    }

    public function qualifyIndex()
    {
        $params = $this->request->param();

        api_validate(JobValidate::class, 'index', $params);

        $ret = $this->jobService->qualifyIndex($params);

        return json($ret);
    }


    /**
     * 特种作业操作证书类别
     * @return \think\response\Json
     */
    public function specialWorkIndex()
    {
        $specialData = ObjectConstant::SPECIAL_WORK_OBJECT;
        $specialWork = [];
        foreach ($specialData as $k => $v) {
            $specialWork[] = $v;
        }

        $ret = [
            'list' => $specialWork
        ];

        return json(result_successed($ret));
    }

    /*
     * PPE
     */
    public function ppeIndex()
    {
        $params = $this->request->param();

        $ret = (new JobService())->ppeIndex($params);
        return json($ret);
    }

    public function ppeAdd()
    {
        $params = $this->request->only([
//            'job_id',
            'ppe_type_id',
            'name',
            'type',
            'spec',
            'brand',
            'firm_rate',
            'attribute',
            'firm_rate_type',
        ]);

        api_validate(PpeValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->ppeAdd($params);
        return json($ret);

    }
    public function ppeEdit()
    {
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([
            'id',
//            'job_id',
            'ppe_type_id',
            'name',
            'type',
            'spec',
            'brand',
            'firm_rate',
            'attribute',
            'firm_rate_type',
        ]);
//        dd($params);

        api_validate(PpeValidate::class, 'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $ret = (new JobService())->ppeEdit($params);
        return json($ret);

    }


    /*
     *应急预案列表
     */
    public function emergencyPlanIndex()
    {
        $params = $this->request->param();

        $ret = (new JobService())->emergencyPlanIndex($params);

        return json($ret);
    }
    public function emergencyPlanAdd()
    {
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([
            'department_id',
            'job_id' ,
            'name',
            'evaluate_type',
            'excess_plan',
        ]);

        api_validate(EmerPlanValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;


        $ret = (new JobService())->emergencyPlanAdd($params);
        return json($ret);
    }

    public function emergencyPlanEdit()
    {
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([
            'id',
//            'department_id',
//            'job_id' ,
            'name',
            'evaluate_type',
            'excess_plan',
        ]);

//        api_validate(EmerPlanValidate::class, 'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->emergencyPlanEdit($params);
        return json($ret);

    }



    /**
     * 危险源列表
     * @return \think\response\Json
     */
     public function dangerSourceIndex()
     {
         $params = $this->request->param();

//         api_validate(JobValidate::class, 'index', $params);
         $params['company_id'] = JwtService::getInstance()->getCompanyId();
         $ret = (new JobService())->dangerSourceIndex($params);

         return json($ret);
     }
    /**
     * 重要环境因素列表
     * @return \think\response\Json
     */
    public function environmentFactorIndex()
    {
        $params = $this->request->param();

//         api_validate(JobValidate::class, 'index', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->environmentFactorIndex($params);

        return json($ret);
    }

    public function roleLabelIndex()
    {
        $params = $this->request->param();

//         api_validate(JobValidate::class, 'index', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->roleLabelIndex($params);
        return json($ret);
    }

    public function detail()
    {
        $params = $this->request->param();
        api_validate(CommonValidate::class, 'detail', $params);
        $ret = (new JobService())->detail($params['id']);
        return json($ret);
    }

    /*
     * 岗位绑定 ppe 添加
     */
    public function bindPpeAdd()
    {
        $params = $this->request->param();
        if (empty($params['job_id']) || empty($params['ppeContent'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindPpeAdd($params);
        return json($ret);
    }

    /*
   * 岗位绑定 ppe 编辑
   */
    public function bindPpeEdit()
    {
        $params = $this->request->param();
        if (empty($params['id']) || empty($params['ppe_receive_rate'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindPpeEdit($params);
        return json($ret);
    }

    /*
    * 岗位绑定 ppe 删除
    */
    public function bindPpeDelete()
    {
        $params = $this->request->param();
        if (empty($params['id'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindPpeDelete($params);
        return json($ret);
    }

    /*
     * 岗位绑定课程添加
     */
    public function bindCourseAdd()
    {
        $params = $this->request->param();
        if (empty($params['job_id']) || empty($params['link_ids'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindCourseAdd($params);
        return json($ret);
    }

    /*
    * 岗位绑定 ppe 删除
    */
    public function bindCourseDelete()
    {
        $params = $this->request->param();
        if (empty($params['id'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindCourseDelete($params);
        return json($ret);
    }

    /*
    * 岗位绑定紧急预案添加
    */
    public function bindEmergencyAdd()
    {
        $params = $this->request->param();
        if (empty($params['job_id']) || empty($params['link_ids'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindEmergencyAdd($params);
        return json($ret);
    }

    /*
    * 岗位绑定 紧急预案 删除
    */
    public function bindEmergencyDelete()
    {
        $params = $this->request->param();
        if (empty($params['id'])) {
            return json(result_failed("参数必传"));
        }
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindEmergencyDelete($params);
        return json($ret);
    }


    public function bindPpe()
    {
        $params = $this->request->param();

        $params['identify'] = 'job_ppe';
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bindPpeAdd($params);
        return json($ret);
    }

    public function bindCourse()
    {
        $params = $this->request->param();
        $params['identify'] = 'job_course';
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bind($params);
        return json($ret);
    }

    public function bindEmergency()
    {
        $params = $this->request->param();
        $params['identify'] = 'job_emergency';
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobService())->bind($params);
        return json($ret);
    }

    public function bindPpeDetail()
    {
        $params = $this->request->param();

//        api_validate(CommonValidate::class, 'detail', $params);
        $jobId = $params['id'];

        $ret = (new JobService())->bindPpeDetail($jobId);
        return json($ret);
    }
    public function bindCourseDetail()
    {
        $params = $this->request->param();

//        api_validate(CommonValidate::class, 'detail', $params);
        $jobId = $params['id'];

        $ret = (new JobService())->bindCourseDetail($jobId);
        return json($ret);
    }
    public function bindEmergencyDetail()
    {
        $params = $this->request->param();

//        api_validate(CommonValidate::class, 'detail', $params);
        $jobId = $params['id'];

        $ret = (new JobService())->bindEmergencyDetail($jobId);
        return json($ret);
    }


}
