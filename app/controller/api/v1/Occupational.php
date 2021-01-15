<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\ObjectConstant;
use app\common\service\FacilityService;
use app\common\service\JwtService;
use app\common\service\OccupationalService;
use app\common\validate\FacilityValidate;
use app\common\validate\OcTestPlanValidate;
use think\facade\Db;

/**
 * 职业病有害因素
 */
class Occupational extends ApiBase
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
    public function harmFactorIndex()
    {

        $params = $this->request->param();

//        $companyId = JwtService::getInstance()->getCompanyId();
//        $params['company_id'] = $companyId;
//        $params['type']       = $params['type'] ?? 1;

        $ret = (new OccupationalService())->harmFactorIndex($params);

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

    /*
     *职业禁忌症
     */
    public function ocTabooIndex()
    {
        $params = $this->request->param();
        $ret = (new OccupationalService())->ocTabooIndex($params);
        return json($ret);
    }


    /**
     * 根据危害因素id获取职业病和职业病禁忌
     * @return \think\response\Json
     */
    public function ocDetail()
    {
        $id = $this->request->param('id');
        if (empty($id)) {
            return json(result_failed('id 必须'));
        }

        $data = Db::name('oc_harm_factor')
            ->where('id', $id)
            ->find();
//            ->value('before_target_item');
        $before_target_item = $data['before_target_item'];
        $oc_harm_factor_base_id = $data['oc_harm_factor_base_id'];

        // oc_harm_factor_base_id
        $taboo = ObjectConstant::TABOO_TYPE;
        $oc_taboo = $taboo[$oc_harm_factor_base_id]['pre_work'] ?? '';

        $ret = [
            'taboo' => $oc_taboo,
            'oc'    => $before_target_item,
        ];

        return json(result_successed($ret));
    }

    /*
     * 职业病体检计划
     */
    public function testPlanIndex()
    {
        $params = $this->request->param();
        $ret = (new OccupationalService())->testPlanIndex($params);
        return json($ret);
    }

    public function testPlanAdd()
    {
        $params = $this->request->only([
//            'company_id',
            'user_id',
            'department_id',
            'job_id',
            'before_time',
            'next_time',
//            'test_item',
            'is_job',

        ]);

        api_validate(OcTestPlanValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new OccupationalService())->testPlanAdd($params);
        return json($ret);
    }

    public function testPlanEdit()
    {
//        $params = $this->request->only([
//            'id',
//            'user_id',
//            'department_id',
//            'job_id',
//            'before_time',
//            'next_time',
//            'test_item',
//            'is_job',
//        ]);

        $params = $this->request->param();

        api_validate(OcTestPlanValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
//        $params['type']       = $params['type'] ?? 1;

        $ret = (new OccupationalService())->testPlanEdit($params);
        return json($ret);
    }




}
