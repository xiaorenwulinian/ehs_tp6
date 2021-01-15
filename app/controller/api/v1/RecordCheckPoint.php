<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\service\JwtService;
use app\common\service\RecordCheckPointService;
use app\common\library\StringLib;
use think\facade\Db;

/**
 * 公司区域
 */
class RecordCheckPoint extends ApiBase
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

//        api_validate(JobValidate::class, 'index', $params);

        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret = (new RecordCheckPointService())->index($params);

        return api_successed($ret);

    }

    /**
     * 岗位添加
     * @return \think\response\Json
     */
    public function add()
    {

        $len = rand(500,10000);

        $insertArr = [];
        for ($i = 0; $i < $len; $i++) {
            $temp = [
                'company_id' => 1,
                'no'         => microtime(true) . StringLib::random(10),
                'ehs_point_id' => rand(1, 5),
                'ehs_point_check_time_id'   => rand(1, 5),
                'name'    => StringLib::random(4, 4),
                'desc'    => StringLib::random(8, 4),
                'content' => StringLib::random(16, 4),
                'atime'   => time() -  rand(1,1000) * 60,
                'sort'    => rand(20,99),
                'ctime'   => time(),
                'mtime'   => time(),
            ];
            array_push($insertArr, $temp);
        }

        Db::name('record_check_point')->insertAll($insertArr);

        return 'ok';
     }


}
