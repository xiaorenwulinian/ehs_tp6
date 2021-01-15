<?php

namespace app\common\service;

use app\common\library\jwt\JwtLib;

/**
 *
 * Class CheckRateService
 * @package app\common\service
 */
class JwtService {

    private static $instance = null;

    private function __construct()
    {
    }

    /**
     * @return null
     */
    public static function getInstance() : JwtService
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }


    /**
     * api 生成token
     * @param Array $user 当前用户
     * @return string
     */
    public function generateTokenApi(Array $user)
    {

        if (!empty($user['id'])) {
            $user['user_id'] = $user['id'];
        }

        $secretKey = 'token_mobile';
        $time = time();
        $expireTime = $time + 3600 * 24 * 30;
        $secretToken = array(
//            "iss"       => "",
//            "aud"       => "",
            "iat"       => $time,
            "nbf"       => $time,
            "exp"       => $expireTime,
            "user_id"   => $user['user_id'], // 用户ID
            "user_info" => $user, // 不建议存太多信息，用户ID和手机号即可，敏感信息会被窃取
        );
        try {
            $jwtToken = JwtLib::encrypt($secretToken, $secretKey);

        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }

        return $jwtToken;
    }


    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function getUserIdMobile()
    {
        $userinfo = $this->getUserInfoMobile();
        return $userinfo['user_id'];
    }


    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function getUserInfoMobile()
    {
        $secretKey = 'token_mobile';

        $authorization = request()->header('authorization');

        if (empty($authorization) || false === stristr($authorization, ' ')) {
            return api_failed("token 格式错误", 401);
        }

        list($bearer, $jwtToken) = explode(' ', $authorization);

        if ($bearer != 'bearer') {
//            return  api_failed('');
        }
//            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
        $result = JwtLib::decrypt($jwtToken, $secretKey, array('HS256'));

        if ($result['code'] === 1) {

            return api_failed($result['message'], 401);
        }

        if ($result['code'] != 0) {
            return api_failed($result['message'], 401);
        }
        $decoded = $result['data'];

        $decodedArray = (array)$decoded;

        $userInfo =  (array)$decodedArray['user_info'];

        return $userInfo;
    }

    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function getUserIdApi()
    {

        $userInfo = $this->getUserInfoApi();
        $uid =  $userInfo['user_id'];

        return $uid;
    }


    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function getUserInfoApi()
    {
        $secretKey = 'token_mobile';

        $authorization = request()->header('authorization');

        if (empty($authorization) || false === stristr($authorization, ' ')) {
            return api_failed("token 格式错误", 401);
        }

        list($bearer, $jwtToken) = explode(' ', $authorization);

        if ($bearer != 'bearer') {
//            return  api_failed('');
        }
//            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
        $result = JwtLib::decrypt($jwtToken, $secretKey, array('HS256'));

        if ($result['code'] === 1) {

            return api_failed($result['message'], 401);
        }

        if ($result['code'] != 0) {
            return api_failed($result['message'], 401);

        }
        $decoded = $result['data'];

        $decodedArray = (array)$decoded;
        $userInfo =  (array)$decodedArray['user_info'];

        return $userInfo;
    }

    public function getUserInfo()
    {
        $userInfo = $this->getUserInfoApi();
        return $userInfo;
    }

    public function getCompanyId()
    {
        $userInfo = $this->getUserInfoApi();
        return $userInfo['company_id'];
    }

    public function getUserId()
    {
        $userInfo = $this->getUserInfoApi();
        return $userInfo['id'];
    }


}