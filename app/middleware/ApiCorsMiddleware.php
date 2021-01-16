<?php
declare (strict_types = 1);

namespace app\middleware;

use think\Response;

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

//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Max-Age: 1800');
//        header('Access-Control-Allow-Methods: GET, POST');
//        header('Access-Control-Allow-Headers: Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, Token');
//        if (strtoupper($request->method()) == "OPTIONS") {
//            return Response::create()->send();
//        }
//
//        return $next($request);


        $web = request()->header('Origin');
//跨域请求设置
        header("Access-Control-Request-Method:GET,POST");
        header("Access-Control-Allow-Credentials:true");
//        header("Access-Control-Allow-Origin:".$web);
        header("Access-Control-Allow-Origin: *");
//        $header = "token,Content-Type, Authorization, Accept, Range, Origin,Token,Lang,lang,x-requested-with,os-version";

        $headerArr = [
            "token",
            "Content-Type",
            "Authorization",
            "authorization",
            "Accept",
            "Range",
            "Origin",
            "Token",
            "Lang",
            "lang",
            "x-requested-with",
            "os-version",
            "height",
            "preflight",
            "x-csrf-token,x-requested-with",
            "device-name",
            "os",
            'content-type',
            'height',
            'os-version',
            'Referer',
            'width',
            'User-Agent',

        ];
        $headerArr = ["*"];
        $header = implode(',', $headerArr);
        header("Access-Control-Allow-Headers: {$header}");

//        header('Access-Control-Allow-Headers:x-requested-with,content-type,Authorization')
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
