<?php

namespace app\controller\mobile;

use app\BaseController;
use app\common\service\JwtService;
use think\Request;

class MobileBase extends BaseController
{

    /**
     * @var array 免登录方法， * 代表当前控制器所有
     */
    protected $noNeedLogin = [];

    /**
     * @var array 免权限方法， * 代表当前控制器所有
     */
    protected $noNeedAuth = [];


    public function _initialize()
    {

        //跨域请求检测
//        check_cors_request();

        // 验证 token
       /* $module     = \request()->module();
        $controller = \request()->controller();
        $action     = \request()->action();

        if (!in_array('*', $this->noNeedLogin)) {

            if ( !in_array($action, $this->noNeedLogin)) {

                JwtService::getInstance()->getUserIdMobile();
            }
        }

        // auth
        if (!in_array('*', $this->noNeedAuth)) {

            if ( !in_array($action, $this->noNeedAuth)) {

                $this->isAuth();
            }
        }*/

    }

    protected function isAuth()
    {

    }


}