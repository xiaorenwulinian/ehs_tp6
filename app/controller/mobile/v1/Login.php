<?php

namespace app\controller\mobile\v1;


use app\common\service\UserService;
use app\common\validate\UserValidate;
use app\controller\mobile\MobileBase;


class Login extends MobileBase
{

    protected $noNeedLogin = ["*"];
    protected $noNeedAuth = ["*"];

    public function login()
    {
        $params = $this->request->only([
            'username',
            'password',
            'company_name'
        ], 'POST');

        api_validate(UserValidate::class, 'mobile_login', $params);

//        $result = (new UserService())->mobileLogin($params);
        $result = (new UserService())->login($params);
        return api_successed($result);

    }



}