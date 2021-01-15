<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\service\EhsPointCheckTimeService;
use app\common\validate\DepartmentValidate;
use app\common\validate\EhsPointCheckTimeValidate;

/**
 * 部门
 */
class EhsPointCheckTime extends ApiBase
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

        api_validate(DepartmentValidate::class, 'index', $params);

        $ret = (new EhsPointCheckTimeService())->index($params);

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
            'company_id',
            'company_area_id',
            'space_num',
            'point_name',
            'desc',
            'sort',
            'state',
        ]);

        api_validate(EhsPointCheckTimeValidate::class, 'add', $params);

        $ret = (new EhsPointCheckTimeService())->add($params);

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
         if ($this->request->isGet()) {
             return api_failed("非法请求");
         }

         $params = $this->request->only([
             'id',
             'company_id',
             'company_area_id',
             'space_num',
             'point_name',
             'desc',
             'sort',
             'state',
         ]);

         api_validate(EhsPointCheckTimeValidate::class, 'edit', $params);


         $ret = (new EhsPointCheckTimeService())->edit($params);
         return json($ret);

     }




}
