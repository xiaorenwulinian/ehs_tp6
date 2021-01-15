<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\WorkConstant;
use app\common\service\JwtService;
use app\common\service\WorkService;
use app\common\validate\WorkFireValidate;
use app\common\validate\WorkHighValidate;
use app\common\validate\WorkDirtValidate;

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
        $params['work_link_type'] = WorkConstant::WORK_LIMIT_SPARE;
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
