<?php

namespace app\controller\mobile\v1;

use app\common\service\JwtService;

use app\controller\mobile\MobileBase;
use think\facade\Db;
use think\Request;

class User extends MobileBase
{

    protected $noNeedLogin = ["*"];
    protected $noNeedAuth  = ["*"];



    public function detail()
    {

        $userid = JwtService::getInstance()->getUserIdMobile();
        $user = Db::name('user')->where('id', $userid)->find();

        $company = Db::name('company')
            ->where('company_id', $user['company_id'])
            ->find();

        $user['company_name'] = $company['title'];
//        dd($user);
        $ret = [
            'info'    => $user,
//            'company' => $company,
        ];
        return api_successed($ret);
    }

}