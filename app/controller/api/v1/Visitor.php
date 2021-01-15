<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\service\JobAvoidService;
use app\common\service\JwtService;
use app\common\service\VisitorService;
use app\common\validate\CommonValidate;
use app\common\validate\JobAvoidValidate;
use app\common\controller\Api;
use app\common\validate\VisitorValidate;

/**
 * 访客信息
 */
class Visitor extends ApiBase
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

//        api_validate(CommonValidate::class, 'index', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new VisitorService())->index($params);

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
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([
            'company_area_id',
            'visitor_name',
            'receiver',
            'visitor_idcard',
            'reasons',
            'arrive_time',
            'phone',
            'bracelet_status',
        ]);

        api_validate(VisitorValidate::class, 'add', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();



        $ret =  (new VisitorService())->add($params);
        return json($ret);

     }


}
