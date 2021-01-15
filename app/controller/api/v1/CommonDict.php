<?php


namespace app\controller\api\v1;

use app\common\constant\CommonDictConstant;
use app\common\service\CommonDictService;
use app\common\service\JwtService;
use app\common\validate\CommonDictValidate;
use app\controller\api\ApiBase;


/**
 * 常用字典
 */
class CommonDict extends ApiBase
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

        api_validate(CommonDictValidate::class, 'index', $params);

        $tableArr = CommonDictConstant::TABLE_ARR;

        if (!array_key_exists($params['type'], $tableArr)) {
            return json(result_failed("该类型不合法！"));
        }

        $params['table_name'] = $tableArr[$params['type']];
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new CommonDictService())->index($params);

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
             'type',
             'content',
         ]);

         api_validate(CommonDictValidate::class, 'edit', $params);
         $tableArr = CommonDictConstant::TABLE_ARR;

         if (!array_key_exists($params['type'], $tableArr)) {
             return json(result_failed("该类型不合法！"));
         }

         $params['table_name'] = $tableArr[$params['type']];
         $params['company_id'] = JwtService::getInstance()->getCompanyId();

         $ret = (new CommonDictService())->edit($params);

         return json($ret);

     }




}
