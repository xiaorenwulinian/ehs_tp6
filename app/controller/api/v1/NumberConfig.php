<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;

use app\common\constant\NumberConfigConstant;
use app\common\service\JwtService;
use app\common\service\NumberConfigService;
use app\common\validate\NumberConfigValidate;
use think\Db;


/**
 * 编号配置
 */
class NumberConfig extends ApiBase
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];


    public function _initialize()
    {
        parent::_initialize();


    }

    public function detail()
    {
        $params = $this->request->param();
        $identify = $params['identify'] ?? '';
        $numberAll = NumberConfigConstant::NUMBER_ARR;
        if (!in_array($identify, $numberAll)) {
            return json(result_failed('错误的标识identify'));
        }

        $companyId = JwtService::getInstance()->getCompanyId();
        $data = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();
        if (!$data) {
            $data = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }

        return json(result_successed(['info' => $data]));
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
             'ext_select',
             'identify',
             'serial_length',
             'prefix_alp',
         ]);

         api_validate(NumberConfigValidate::class, 'edit', $params);

         $userInfo  = JwtService::getInstance()->getUserInfoApi();

         $numberAll = NumberConfigConstant::NUMBER_ARR;

         if (!in_array($params['identify'], $numberAll)) {
             return json(result_failed("该类型不合法！"));
         }

         $params['company_id'] = $userInfo['company_id'];

         $ret = (new NumberConfigService())->edit($params);

         return json($ret);

     }




}
