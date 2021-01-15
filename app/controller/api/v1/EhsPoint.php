<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\EhsPointService;
use app\common\service\EhsCourseService;
use app\common\service\JwtService;
use app\common\validate\EhsPointValidate;
use app\common\validate\EhsCourseValidate;

/**
 * 环境监测点
 */
class EhsPoint extends ApiBase
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

        api_validate(EhsPointValidate::class, 'index', $params);

        $ret = (new EhsPointService())->index($params);

        return json($ret);

    }


    /**
     * @descption 部门添加
     * @author lcl
     * @date 2020/11/23
     * @return \think\response\Json
     */
    public function add()
    {

        $params = $this->request->only([
            'job_id',
            'company_area_id',
            'ehs_point_check_time_id',
            'standard_id',
            'point_name',
            'check_content',
            'desc',
            'sort',
        ]);

        api_validate(EhsPointValidate::class, 'add', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new EhsPointService())->add($params);
        return json($ret);

     }

    /**
     * 修改
     * @author lcl
     * @date 2020/11/23
     * @return \think\response\Json
     */
     public function edit()
     {

         $params = $this->request->only([
             'ehs_point_id',
             'job_id',
             'company_area_id',
             'ehs_point_check_time_id',
             'ehs_point_check_time_id',
             'standard_id',
             'point_name',
             'check_content',
             'desc',
             'sort',
         ]);

         api_validate(EhsPointValidate::class, 'edit', $params);
         $params['company_id'] = JwtService::getInstance()->getCompanyId();
         $ret = (new EhsPointService())->edit($params);
         return json($ret);

     }


    /**
     * 删除
     * @author lcl
     * @date 2020/11/25
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delete()
    {
        $id = input('ehs_point_id/d',0);


        (new EhsPointService())->delete($id);

        return json(result_successed());

    }


    /*
     * ehs课程列表
     */
    public function courseIndex()
    {
        $params = $this->request->param();

//        api_validate(EhsCourseValidate::class, 'index', $params);

        $ret = (new EhsCourseService())->courseIndex($params);

        return json($ret);

    }


    /**
     * @descption ehs课程添加
     * @return \think\response\Json
     */
    public function courseAdd()
    {
        $params = $this->request->only([
            'job_id',
            'name',
            'hour',
            'integration',
            'type',
            'is_online',
        ]);

        api_validate(EhsCourseValidate::class, 'add', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new EhsCourseService())->courseAdd($params);
        return json($ret);

    }

}
