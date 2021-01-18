<?php


namespace app\controller\api\v1;


use app\common\service\JwtService;
use app\controller\api\ApiBase;
use think\facade\Db;
use think\Request;

/**
 * 安全检查频次
 */
class Strategy extends ApiBase
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function policyShow(Request $request)
    {
        $type = $request->param('type',0);
        $policy = [
            1 => '环境方针',
            2 => '职业健康安全方针',
            3 => '能源方针',
        ];
        if (!array_key_exists($type, $policy)) {
            return json(result_failed("类型传参错误"));
        }

        $companyId = JwtService::getInstance()->getCompanyId();
        $info =  Db::table('strategy_policy')
            ->where('type', $type)
            ->where('company_id', $companyId)
            ->find();

        return json(result_successed(compact('info')));

    }

    public function policySave(Request $request)
    {
        $type = $request->param('type',0);
        $policy = [
            1 => '环境方针',
            2 => '职业健康安全方针',
            3 => '能源方针',
        ];
        if (!array_key_exists($type, $policy)) {
            return json(result_failed("类型传参错误"));
        }

        $companyId = JwtService::getInstance()->getCompanyId();
        $info =  Db::table('strategy_policy')
            ->where('type', $type)
            ->where('company_id', $companyId)
            ->find();

        return json(result_successed(compact('info')));

    }


}
