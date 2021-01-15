<?php

namespace app\controller\api;



use app\BaseController;

class ApiBase extends BaseController
{

    /**
     * @var array 免登录方法， * 代表当前控制器所有
     */
    protected $noNeedLogin = ['*'];

    /**
     * @var array 免权限方法， * 代表当前控制器所有
     */
    protected $noNeedRight = ['*'];




    public function initialize()
    {

        //跨域请求检测
        check_cors_request();

        $this->_initialize();

    }

    public function _initialize()
    {

    }

    protected function check()
    {
        return true;
    }


    public function _empty()
    {
        //把所有城市的操作解析到city方法
//        $controller = request()->controller();
//        $action = request()->action();
        $route = request()->baseUrl();
        $msg = "路由不存在：" . $route;
        return api_failed($msg);
    }


}