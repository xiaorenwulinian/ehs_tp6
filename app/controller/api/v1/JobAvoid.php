<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\JobAvoidService;
use app\common\service\JwtService;
use app\common\validate\JobAvoidValidate;

/**
 * 岗位-禁忌
 */
class JobAvoid extends ApiBase
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

//        api_validate(JobAvoidValidate::class, 'index', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new JobAvoidService())->index($params);

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
            'avoid_name',
            'desc',
        ]);

        api_validate(JobAvoidValidate::class, 'add', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        return json((new JobAvoidService())->add($params));

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
             'avoid_name',
             'desc',
         ]);

         api_validate(JobAvoidValidate::class, 'edit', $params);

         $params['company_id'] = JwtService::getInstance()->getCompanyId();
         return json((new JobAvoidService())->edit($params));

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

        return json((new JobAvoidService())->delete($id));

    }

}
