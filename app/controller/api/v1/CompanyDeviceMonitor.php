<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\CompanyDeviceMonitorService;
use app\common\service\JwtService;
use app\common\validate\CompanyDeviceMonitorValidate;

/**
 * 部门
 */
class CompanyDeviceMonitor extends ApiBase
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $params = $this->request->param();

//        api_validate(CompanyDeviceMonitorValidate::class, 'index', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new CompanyDeviceMonitorService())->index($params);

        return json($ret);

    }


    /**
     * @descption 添加
     * @author lcl
     * @date 2020/11/27
     * @return \think\response\Json
     */
    public function add()
    {
        $params = $this->request->only([
            'company_area_id',
            'device_name',
            'type',
            'device_state',
            'duty_user_id',
            'spec',
            'note',
            'desc',
        ]);

        api_validate(CompanyDeviceMonitorValidate::class, 'add', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new CompanyDeviceMonitorService())->add($params);
        return json($ret);

     }

    /**
     * 修改
     * @author lcl
     * @date 2020/11/27
     * @return \think\response\Json
     */
     public function edit()
     {
         $params = $this->request->only([
             'id',
             'company_area_id',
             'device_name',
             'type',
             'device_state',
             'duty_user_id',
             'spec',
             'note',
             'desc',
         ]);

         api_validate(CompanyDeviceMonitorValidate::class, 'edit', $params);
         $params['company_id'] = JwtService::getInstance()->getCompanyId();

         $ret = (new CompanyDeviceMonitorService())->edit($params);
         return json($ret);

     }


    /**
     * 删除
     * @author lcl
     * @date 2020/11/27
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delete()
    {
        $id = input('id/d',0);

        $ret = (new CompanyDeviceMonitorService())->delete($id);
        return json($ret);


    }

}
