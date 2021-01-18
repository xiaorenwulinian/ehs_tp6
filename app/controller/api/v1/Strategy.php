<?php


namespace app\controller\api\v1;


use app\common\service\JwtService;
use app\common\service\StrategyService;
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

    /**
     * 方针显示
     * @param Request $request
     * @return \think\response\Json
     */
    public function policyShow()
    {
        $type = $this->request->param('type',0);
        $policy = [
            1 => '环境',
            2 => '职业健康安全',
            3 => '能源',
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

    /**
     * 方针保存
     * @param Request $request
     * @return \think\response\Json
     */
    public function policySave(Request $request)
    {
        $id = $this->request->param('id',0);
        $content = $this->request->param('content','');

        $companyId = JwtService::getInstance()->getCompanyId();
        $info =  Db::table('strategy_policy')
            ->where('id', $id)
            ->where('company_id', $companyId)
            ->find();
        if (!$info) {
            return json(result_failed("未发现该对象"));
        }

        Db::table('strategy_policy')
            ->where('id', $id)
            ->update([
                'content' => $content
            ]);

        return json(result_successed());

    }

    /*
     * 战略目标 列表
     */
    public function goalIndex()
    {
        $params = $this->request->param();
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new StrategyService())->goalIndex($params);

        return json($ret);
    }

    /*
     * 战略目标 添加
     */
    public function goalAdd()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new StrategyService())->goalAdd($params);

        return json($ret);
    }

    /*
   * 战略目标 编辑
   */
    public function goalEdit()
    {
        $params = $this->request->param();

        $params['company_id'] = JwtService::getInstance()->getCompanyId();

        $ret = (new StrategyService())->goalEdit($params);

        return json($ret);
    }

    /*
     *
    * 战略目标 删除
   */
    public function goalDelete()
    {
        $params = $this->request->param();
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new StrategyService())->goalDelete($params);

        return json($ret);
    }




}
