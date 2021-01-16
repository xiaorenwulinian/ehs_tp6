<?php
declare (strict_types = 1);

namespace app\middleware;

class ApiCorsMiddleware
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

//        $response = $next($request);
//        $response->header('Access-Control-Allow-Origin','*');
//        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept');
//        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//        $response->header('Access-Control-Allow-Credentials', 'true');
//        return $response;
        //跨域请求检测

        $web = request()->header('Origin');
//跨域请求设置
        header("Access-Control-Request-Method:GET,POST");
        header("Access-Control-Allow-Credentials:true");
//        header("Access-Control-Allow-Origin:".$web);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers:token,Content-Type, Authorization, Accept, Range, Origin,Token,Lang,lang");
//        if($this->request->isOptions()){
//            exit;
//        }
//        header("Access-Control-Allow-Origin: *");
//        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Allow-Headers: Origin, Content-Type, Cookie, Accept');
//        header('Access-Control-Max-Age: 86400');
//        header('Access-Control-Allow-Headers:x-requested-with,Content-Type,X-CSRF-Token');
//
        return $next($request);
    }
}
