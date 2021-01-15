<?php

namespace app\controller\mobile\v1;

use app\common\library\StringLib;
use app\common\service\JwtService;
use app\controller\mobile\MobileBase;
use think\facade\Db;

class Plan extends MobileBase
{

    protected $noNeedLogin = ["*"];
    protected $noNeedAuth  = ["*"];

    /*
     * 今日计划
     */
    public function curDay()
    {
        $params = $this->request->param();

        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $data = $this->mockDay();
        $list = array_slice($data, $offset, $pageSize);

        return api_successed(compact('list'));
    }

    private function mockDay()
    {

        $cache = cache('mock_plan_day');
        if ($cache) {
            return unserialize($cache);
        }
        $data = [];
        for ($i = 0; $i < 15; $i++) {
            $arr = [
                'id'          => ($i + 1),
                'content'     => StringLib::random(rand(10,40),4),
                'duty_person' => StringLib::random(rand(2,3),4),
                'type'        => rand(1,3),
            ];
            $data[] = $arr;
        }

        cache('mock_plan_day', serialize($data));

        return $data;
    }


}