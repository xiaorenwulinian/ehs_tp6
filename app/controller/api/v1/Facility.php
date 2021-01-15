<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\FacilityService;
use app\common\service\JwtService;
use app\common\validate\FacilityValidate;

/**
 * 设施
 */
class Facility extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * @return \think\response\Json
     */
    public function index()
    {

        $params = $this->request->param();

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $params['type']       = $params['type'] ?? 1;

        $ret = (new FacilityService())->index($params);

        return json($ret);

    }

    public function add()
    {
        $params = $this->request->only([
            'company_area_id',
            'type',
            'name',
            'start_time',
            'status',
            'maintain_frequent',
            'department_id',
            'leader_job_id',
            'worker_job_id',
            'check_time_ids',
            'rfid_ids',
            'sop_ids',
            'accept_data',
            'instruction',
            'damage_goods_content',
            'danger_source_content',
            'environment_factor_content',
        ]);

        api_validate(FacilityValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new FacilityService())->add($params);
        return json($ret);
    }

    public function edit()
    {
//        $params = $this->request->only([
//            'id',
//            'name',
//            'status',
//            'maintain_frequent',
//            'sop_ids',
//            'accept_data',
//            'instruction',
//        ]);

        $params = $this->request->param();

        api_validate(FacilityValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
//        $params['type']       = $params['type'] ?? 1;

        $ret = (new FacilityService())->edit($params);
        return json($ret);
    }

    public function detail()
    {
        $params = $this->request->param();

        api_validate(FacilityValidate::class,'detail', $params);


        $ret = (new FacilityService())->detail($params['id']);
        return json($ret);
    }




}
