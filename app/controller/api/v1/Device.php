<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\DeviceService;
use app\common\service\JwtService;
use app\common\validate\BraceletDeviceValidate;
use app\common\validate\DeviceLocationPointValidate;
use app\common\validate\DevicePatrolPointValidate;
use app\common\validate\RfidDeviceValidate;
use app\common\validate\DeviceIdentifyValidate;
use app\common\validate\DeviceCameraValidate;
use app\common\validate\DeviceSpaceValidate;

/**
 * 设备
 */
class Device extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    public function rfidIndex()
    {

        $params = $this->request->param();

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->rfidIndex($params);

        return json($ret);

    }

    public function rfidAdd()
    {
        $params = $this->request->only([
            'name',
            'type',
            'scene',
            'ip',
            'line_num',
            'identify_code',
            'line_code',
        ]);

        api_validate(RfidDeviceValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->rfidAdd($params);
        return json($ret);
    }




    public function rfidEdit()
    {
        $params = $this->request->only([
            'id',
            'name',
            'type',
            'scene',
            'ip',
            'identify_code',
            'line_code',
        ]);

        api_validate(RfidDeviceValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->rfidEdit($params);
        return json($ret);
    }


    /*
     * 定位点
     */
    public function locationPointIndex()
    {

        $params = $this->request->param();

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->locationPointIndex($params);

        return json($ret);
    }

    public function locationPointAdd()
    {

        $params = $this->request->only([
            'company_area_id',
            'device_status',
            'radius',
            'rfid_id',
            'department_id',
        ]);

        api_validate(DeviceLocationPointValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
//        $params['type']       = 2;

        $ret = (new DeviceService())->locationPointAdd($params);
        return json($ret);
    }

    /*
     * 巡检点（安检点）
     */
    public function patrolPointIndex()
    {

        $params = $this->request->param();

//        api_validate(DevicePatrolPointValidate::class,'index', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->patrolPointIndex($params);

        return json($ret);
    }

    public function patrolPointAdd()
    {

        $params = $this->request->only([
            'company_area_id',
            'device_status',
            'name',
        ]);

        api_validate(DevicePatrolPointValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
//        $params['type']       = 2;

        $ret = (new DeviceService())->patrolPointAdd($params);
        return json($ret);
    }

    public function patrolPointEdit()
    {

        $params = $this->request->only([
            'id',
//            'company_area_id',
//            'device_status',
            'name',
        ]);

        api_validate(DevicePatrolPointValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
//        $params['type']       = 2;

        $ret = (new DeviceService())->patrolPointEdit($params);
        return json($ret);
    }

    /*
     * 点检点（点检位）
     */
    public function checkPointIndex()
    {

        $params = $this->request->param();

        api_validate(DevicePatrolPointValidate::class,'index', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->checkPointIndex($params);

        return json($ret);
    }

    /*
     * 手环发卡器
     */
    public function braceletMachineIndex()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new DeviceService())->braceletMachineIndex($params);

        return json($ret);

    }
    public function braceletMachineAdd()
    {
        $params = $this->request->only([
            'name',
            'specification',
            'device_status',
            ]);
        api_validate(BraceletDeviceValidate::class,'add',$params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new DeviceService())->braceletMachineAdd($params);
        return json($ret);
    }

    public function braceletMachineEdit()
    {
        $params = $this->request->only([
            'id',
            'name',
            'specification',
        ]);
        api_validate(BraceletDeviceValidate::class,'edit',$params);
        $companyId=JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $ret = (new DeviceService())->braceletMachineEdit($params);
        return json($ret);
    }

    /*
     * 身份证读卡器
     */

    public function identifyMachineIndex()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new DeviceService())->identifyMachineIndex($params);

        return json($ret);

    }
    public function identifyMachineAdd()
    {
        $params = $this->request->only([
            'name',
            'duty_user_id',
            'device_status'
        ]);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        api_validate(DeviceIdentifyValidate::class,'add',$params);

        $ret = (new DeviceService())->identifyMachineAdd($params);
        return json($ret);
    }

    public function identifyMachineEdit()
    {
        $params = $this->request->only([
            'id',
            'name',
        ]);
        api_validate(DeviceIdentifyValidate::class,'edit',$params);
        $companyId=JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;
        $ret = (new DeviceService())->identifyMachineEdit($params);
        return json($ret);
    }
    /*
     * 摄像头设置
     */

    public function cameraIndex()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new DeviceService())->cameraIndex($params);
        return json($ret);
    }
    public function cameraAdd()
    {

        $params = $this->request->only([
            'company_area_id',
            'department_id',
            'install_location',
            'specification',
            'brand',
            'ip',
            'start_time',
            'device_status',
        ]);

        api_validate(DeviceCameraValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->cameraAdd($params);
        return json($ret);
    }

    public function cameraEdit()
    {

        $params = $this->request->only([
            'id',
            'ip',
            'specification',
            'brand',
        ]);

        api_validate(DeviceCameraValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->cameraEdit($params);
        return json($ret);
    }

    /*
     *有限空间设置
     */

    public function limitedSpaceIndex()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new DeviceService())->limitedSpaceIndex($params);
        return json($ret);
    }

    public function limitedSpaceAdd()
    {

        $params = $this->request->only([
            'company_area_id',
            'department_id',
            'duty_user_id',
            'name',
            'camera_id',
        ]);

        api_validate(DeviceSpaceValidate::class,'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->limitedSpaceAdd($params);
        return json($ret);
    }

    public function limitedSpaceEdit()
    {

        $params = $this->request->param();

        api_validate(DeviceSpaceValidate::class,'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DeviceService())->limitedSpaceEdit($params);
        return json($ret);
    }


}
