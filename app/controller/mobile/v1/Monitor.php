<?php

namespace app\mobile\controller\v1;

use app\common\service\JwtService;

use app\controller\mobile\MobileBase;
use think\facade\Db;

class Monitor extends MobileBase
{

    /**
     * @var array 免登录方法， * 代表当前控制器所有
     */
    protected $noNeedLogin = ['*'];

    /**
     * @var array 免权限方法， * 代表当前控制器所有
     */
    protected $noNeedAuth = ['*'];

    public function node()
    {
        $params = $this->request->only([
            'company_id',
        ]);

        $area = Db::name('company_area')
            ->field(['company_area_id', 'area_name'])
            ->where('is_deleted','=',0)
            ->where('company_id','=',$params['company_id'])
            ->select();

        $point = Db::name('ehs_point')
            ->field([
                'company_area_id',
                'ehs_point_id',
                'ehs_point_check_time_id',
                'point_name'
            ])
            ->where('is_deleted','=',0)
            ->where('company_id','=',$params['company_id'])
            ->select();

        $pointIdArr = [];
        foreach ($point as $v) {
            $pointIdArr[] = $v['ehs_point_id'];
        }

        dd($area, $point, $pointIdArr);




    }

    /**
     * 获取导航栏未读消息数量
     * @return \think\response\Json
     */
    public function navigateBarInfo()
    {
        $userId = JwtService::getInstance()->getUserId();

        $ret = [
            'scene_num' => rand(0,10)
        ];
        return json(result_successed($ret));
    }

}