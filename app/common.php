<?php
// 应用公共文件
if (!function_exists('check_cors_request')) {
    /**
     * 跨域检测
     */
    function check_cors_request()
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN']) {
            $info = parse_url($_SERVER['HTTP_ORIGIN']);
            $domainArr = explode(',', config('fastadmin.cors_request_domain'));
            $domainArr[] = request()->host(true);


            if (in_array("*", $domainArr) || in_array($_SERVER['HTTP_ORIGIN'], $domainArr) || (isset($info['host']) && in_array($info['host'], $domainArr))) {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            } else {
                header('HTTP/1.1 403 Forbidden');
                exit;
            }

            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                }
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
                exit;
            }
        }
    }
}

if (!function_exists('api_successed')) {
    /**
     * api 成功返回体
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \think\response\Json
     */
    function api_successed($data = [], $message = '请求成功', $code = 0, $header = [])
    {
        $result = [
            'code'     => $code,
            'msg'       => $message,
            'data'      => $data,
            'time'      => time(),
        ];
        $response = \think\Response::create($result, 'json', 200)->header($header);
        throw new \think\exception\HttpResponseException($response);
//        return json($result);
    }
}

if (!function_exists('api_failed')) {
    /**
     * api失败返回体
     * @param string $message
     * @param int $code
     * @param array $data
     * @return \think\response\Json
     */
    function api_failed($message = 'errors', $code = 1, $data = [], $header = [])
    {
        $result = [
            'code'     => $code,
            'msg'       => $message,
            'data'      => $data,
            'time'      => time(),
        ];
//        return json($result);
        $response = \think\Response::create($result, 'json', 200)->header($header);
        throw new \think\exception\HttpResponseException($response);
    }
}


if (!function_exists('result_successed')) {
    /**
     * 第三方/services 成功返回组装
     * @param array $data
     * @param int $code
     * @param string $message
     * @return array
     */
    function result_successed($data = [], $message = 'success', $code = 0)
    {
        $ret = [
            'code'    => $code,
            'msg'     => $message,
            'data'    => $data,
        ];
        return $ret;
    }
}

if (!function_exists('result_failed')) {
    /**
     * 第三方/services 失败返回组装
     * @param array $data
     * @param int $code
     * @param string $message
     * @return array
     */
    function result_failed($message = 'error', $code = 1, $data = [] )
    {
        $ret = [
            'code'    => $code,
            'msg'     => $message,
            'data'    => $data,
        ];
        return $ret;
    }
}

if (!function_exists('api_validate')) {
    /**
     * @descption api 参数验证
     * @author lcl
     * @param $validateClass 验证器类
     * @param string $scene 验证场景
     * @param array $params 验证参数
     * @return \think\response\Json
     */
    function api_validate($validateClass, string $scene, array $params = [])
    {
        $validate = new $validateClass;
        $result   = $validate->scene($scene)->check($params);

        if (!$result) {
            return api_failed($validate->getError());
        }

    }
}

if (!function_exists('get_rand_str')) {
    /**
     * 获取随机字符串
     * @param int $randLength  长度
     * @param int $addtime  是否加入当前时间戳
     * @param int $includenumber   是否包含数字
     * @param int $isCap   是否只包含大写字母
     * @return string
     */
    function get_rand_str($randLength=6,$addtime=1,$includenumber=0, $isCap=0){
        if ($includenumber){
            if ($isCap) {
                $chars='ABCDEFGHJKLMNPQEST123456789';
            }else{
                $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
            }
        }else {
            $chars='abcdefghijklmnopqrstuvwxyz';
        }
        $len=strlen($chars);
        $randStr='';
        for ($i=0;$i<$randLength;$i++){
            $randStr.=$chars[rand(0,$len-1)];
        }
        $tokenvalue=$randStr;
        if ($addtime){
            $tokenvalue=$randStr.time();
        }
        return $tokenvalue;
    }
}

if (!function_exists('collection')) {
    function collection($data)
    {
        return collect($data);
    }
}

if (!function_exists('__')) {
    function __($data)
    {
        return $data;
    }
}
