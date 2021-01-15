<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\controller\Api;
use think\Validate;
use app\common\service\CompanyService;

/**
 * 公司接口
 */
class Company extends ApiBase
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * @ApiTitle  (公司详情)
     * @ApiMethod   (Get)
     * @author lwx
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="int", required=true, description="公司ID")
     * @ApiReturn
     */
    public function detail(){
        $id = input('id');
        $data = (new CompanyService())->getDetail($id);
        return json($data);
    }
}
