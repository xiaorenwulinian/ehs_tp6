<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\JobService;
use app\common\service\JwtService;
use app\common\validate\JobSettingValidate;

/**
 * 设备
 */
class JobSetting extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /*
     *上岗点设置
     */

    public function JobSettingIndex()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new JobService())->JobSettingIndex($params);
        return json($ret);
    }

    public function JobSettingAdd()
    {
        $params = $this->request->only([
            'company_area_id',
            'job_id',
            'rfid_id',
            'camera_id',
        ]);

        api_validate(JobSettingValidate::class,'add', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new JobService())->JobSettingAdd($params);
        return json($ret);
    }


}
