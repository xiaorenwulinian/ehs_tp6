<?php

namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\validate\UserValidate;
use app\common\service\UserService;

/**
 * 登录接口
 */
class Login extends ApiBase
{
//    protected $noNeedLogin = ['login','register'];
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * @ApiTitle  (用户注册)
     * @ApiMethod   (POST)
     * @author lwf
     * @ApiParams   (name="account", type="string", required=true, description="用户名")
     * @ApiParams   (name="password", type="string", required=true, description="用户密码")
     * @ApiParams   (name="phone", type="string", required=true, description="手机号码")
     * @ApiParams   (name="company_name", type="string", required=true, description="企业名称")
     * @ApiParams   (name="smscode ", type="string", required=true, description="验证码")
     * @ApiReturn   ({
        'code':200,
        'mesg':'注册成功',
        "data": {
            "token": "a514cfec-b56b-4226-9eb9-e6e05e71167b",
            "comany_name": "上海众保科技有限公司"
        }
      })
     */
    public function register()
    {

        $params = $this->request->only([
            'account', 'password' ,'phone','company_name' ,'smscode'
        ], 'POST');
        $rule = [
            'account' => 'require|max:25',
            'password' => 'require',
            'mobile' => 'require',
            'company_name' => 'require',
            'smscode' => 'require'
        ];

        $result = validate(\app\common\validate\ApiValidate::class)->check($params, $rule);
        if($result == false){
            return api_failed(validate(\app\common\validate\ApiValidate::class)->getError());
        }

        $result = (new UserService)->checkUser($params);
        return json($result);
    }


    /**
     * @descption   (用户登录)
     */
    public function login()
    {
        $params = $this->request->only([
            'username',
            'password',
            'company_name'
        ], 'POST');

        api_validate(UserValidate::class, 'login', $params);

        $result = (new UserService())->login($params);
        return api_successed($result);
    }


}
