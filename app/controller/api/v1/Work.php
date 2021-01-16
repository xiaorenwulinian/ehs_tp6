<?php


namespace app\controller\api\v1;

use app\common\validate\WorkBlindValidate;
use app\common\validate\WorkCuttingOutValidate;
use app\controller\api\ApiBase;
use app\common\constant\WorkConstant;
use app\common\service\JwtService;
use app\common\service\WorkService;
use app\common\validate\WorkFireValidate;
use app\common\validate\WorkHighValidate;
use app\common\validate\WorkDirtValidate;
use app\common\validate\WorkLimitSpareValidate;
use app\common\validate\WorkElectricValidate;
use app\common\validate\WorkSlingValidate;

/**
 * 作业
 */
class Work extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];


    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * 查询
     * @return \think\response\Json
     */
    public function highIndex()
    {
        $params = $this->request->param();
//        api_validate(JobValidate::class, 'index', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_high';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    /*
     * 高处
     */

    public function highAdd()
    {
        $params = $this->request->param();
        api_validate(WorkHighValidate::class, 'add', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_HIGH;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }

    public function highEdit()
    {
        $params = $this->request->param();
        api_validate(WorkHighValidate::class, 'edit', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_HIGH;
        $ret = (new WorkService())->workCommonEdit($params);
        return json($ret);
    }

    public function fireIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_fire';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    /*
     * 动火
     */
    public function fireAdd()
    {
        $params = $this->request->param();
        api_validate(WorkFireValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_FIRE;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }
    /**
     * 动土
     */
    public function dirtIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_dirt';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function dirtAdd()
    {
        $params = $this->request->param();
        api_validate(WorkDirtValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_DIRT;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }
    /*
     *临时用电
     */
    public function electricIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_electric';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function electricAdd()
    {
        $params = $this->request->param();
        api_validate(WorkElectricValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_ELECTRIC;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }
    /*
     *有限空间
     */
    public function limitSpareIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_limit_spare';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function limitSpareAdd()
    {
        $params = $this->request->param();
        api_validate(WorkLimitSpareValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_LIMIT_SPARE;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }
    /*
    *吊装
    */
    public function slingIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_sling';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function slingAdd()
    {
        $params = $this->request->param();
        api_validate(WorkSlingValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_SLING;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }


//    public function fireEdit()
//    {
//        $params = $this->request->param();
//        api_validate(WorkHighValidate::class, 'edit', $params);
//        $companyId = JwtService::getInstance()->getCompanyId();
//        $params['company_id'] = $companyId;
//        $params['work_link_type'] = WorkConstant::WORK_HIGH;
//        $ret = (new WorkService())->workCommonEdit($params);
//        return json($ret);
//    }

/*
 * 断路
 */
    public function cuttingOutIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_sling';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function cuttingOutAdd()
    {
        $params = $this->request->param();
        api_validate(WorkCuttingOutValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_CUTTING_OUT;
        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }


    /*
     * 盲板
     */
    public function blindIndex()
    {
        $params = $this->request->param();
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['table_name'] = 'work_sling';
        $ret = (new WorkService())->workCommonIndex($params);
        return json($ret);
    }
    public function blindAdd()
    {
        $params = $this->request->param();

        api_validate(WorkBlindValidate::class, 'add', $params);

        $valRet = $this->blindsValidate($params);
        if (true !== $valRet) {
            return json(result_failed($valRet));
        }
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['work_link_type'] = WorkConstant::WORK_BLIND;

        $ret = (new WorkService())->workCommonAdd($params);
        return json($ret);
    }

    public function blindsValidate($params)
    {
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

        $validate = [
            1 => [
                '外包现场作业人' => $out_work_user_id,
                '外包单位' => $out_duty_department_id,
                '外包监护人' => $out_monitor_user_id,
                '外包确认人' => $out_confirm_user_id,
                '外包负责人' => $out_charge_user_id,
            ],
            2 => [
//                'out_work_user_id' => $out_work_user_id,
                '现场作业人' => $work_user_id,
                '责任部门' => $duty_department_id,
                '监护人' => $monitor_user_id,
                '确认人' => $confirm_user_id,
                '负责人' => $charge_user_id,
            ],
            3 => [
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
            ]
        ];

        $operate_type = intval($params['operate_type']);
        // 1. 外包， 2本方， 3协作
        $validArr = $validate[$operate_type];

        foreach ($validArr as $k => $v) {
            if (empty($v)) {
                return"字段:{$k}必传";
            }
        }
        return true;
    }


    public function workCommonShow()
    {
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new WorkService())->workCommonShow($params);
        return json($ret);
    }



    public function userSelect()
    {
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new WorkService())->userSelect($params);
        return json($ret);
    }


}
