<?php


namespace app\controller\api\v1;


use app\common\service\CheckRateService;
use app\common\service\JwtService;
use app\common\validate\CheckRateValidate;
use app\controller\api\ApiBase;

/**
 * 安全检查频次
 */
class CheckRate extends ApiBase
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
    public function index()
    {
        $params = $this->request->param();

        api_validate(CheckRateValidate::class, 'index', $params);

        $ret = (new CheckRateService())->index($params);

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
            'check_rate_name',
            'desc',
            'sort',
        ]);

        api_validate(CheckRateValidate::class, 'add', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new CheckRateService())->add($params);
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
             'id',
             'check_rate_name',
             'desc',
             'sort',
         ]);

         api_validate(CheckRateValidate::class, 'edit', $params);
         $params['company_id'] = JwtService::getInstance()->getCompanyId();

         return json((new CheckRateService())->edit($params));

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
        $id = input('id/d',0);

        $ret = (new CheckRateService())->delete($id);

        return json($ret);

    }

}
