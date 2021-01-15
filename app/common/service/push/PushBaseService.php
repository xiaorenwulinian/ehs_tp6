<?php

namespace app\common\service\push;

use think\Cache;

class PushBaseService
{

    /**
     * 生成 token
     * @return string
     */
    public function generateToken() : string
    {
        $token = Cache::get('ehs_token');

        if(empty($token)){
            $token_config = [
                'appId' => 'dcdc435cc4aa11e587bf0242ac1101de',
                'secretKey' => 'InsQbm2rXG5z',
                'shortId'   => '00000010'
            ];;
            $expiredTime = time() + 86401;    // 过期时间
            $raw_mask = $token_config['appId'] . "_" . $expiredTime . "_" . $token_config['secretKey']; // 初始mask
            $hexMask = hash_hmac("sha1", $raw_mask, $token_config['secretKey']); // 对mask加密
            $shortId = $token_config['shortId'];
            $token = compact('shortId', 'expiredTime', 'hexMask');
            $token = implode(':', $token); // 用:连接起来
            Cache::set('dzh_token', $token, 86400);
        }
        return $token;
    }

    /**
     * 验证token
     * @param $token
     * @return bool
     */
    public function verifyToken($token)
    {
        if (substr_count($token, ':') == 2) {
            $token = explode(':', $token);
            $oldShortId = $token[0];
            $expiredTime = $token[1];
            $oldHexMask = $token[2];
            $token_config = [
                'appId' => 'dcdc435cc4aa11e587bf0242ac1101de',
                'secretKey' => 'InsQbm2rXG5z',
                'shortId'   => '00000010'
            ];;
            $raw_mask = $token_config['appId'] . "_" . $expiredTime . "_" . $token_config['secretKey']; // 初始mask
            $hexMask = hash_hmac("sha1", $raw_mask, $token_config['secretKey']);
            $shortId = $token_config['shortId'];
            if($hexMask == $oldHexMask && $oldShortId == $shortId){
                return true;
            }
        }
        return false;
    }

    /**
     * 发送POST请求
     * @param string $url 地址
     * @param string $fields 附带参数，可以是数组，也可以是字符串
     * @param string $userAgent 浏览器UA
     * @param string $httpHeaders header头部，数组形式
     * @param string $username 用户名
     * @param string $password 密码
     * @return mixed
     */
    public function post($url, $fields, $userAgent = '', $httpHeaders = '', $username = '', $password = '') {
        $ret = $this->execute('POST', $url, $fields, $userAgent, $httpHeaders, $username, $password);
        if (false === $ret) {
            return false;
        }
        if (is_array($ret)) {
            return false;
        }
        return $ret;
    }

    /**
     * GET
     * @param string $url 地址
     * @param string $userAgent 浏览器UA
     * @param string $httpHeaders header头部，数组形式
     * @param string $username 用户名
     * @param string $password 密码
     * @return mixed
     */
    public function get($url, $userAgent = '', $httpHeaders = '', $username = '', $password = '') {
        $ret = $this->execute('GET', $url, "", $userAgent, $httpHeaders, $username, $password);
        if (false === $ret) {
            return false;
        }
        if (is_array($ret)) {
            return false;
        }
        return $ret;
    }

    /**
     *
     * @param string $method 请求方式
     * @param string $url 地址
     * @param string $fields 附带参数，可以是数组，也可以是字符串
     * @param string $userAgent 浏览器UA
     * @param string $httpHeaders header头部，数组形式
     * @param string $username 用户名
     * @param string $password 密码
     * @return mixed
     */
    public  function execute($method, $url, $fields = '', $userAgent = '', $httpHeaders = '', $username = '', $password = '') {
        $ch = $this->create();
        if (false === $ch) {
            return false;
        }
        if (is_string($url) && strlen($url)) {
            $ret = curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            return false;
        }
        //是否显示头部信息
        curl_setopt($ch, CURLOPT_HEADER, false);
        //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($username != '') {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        }

        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }

        $method = strtolower($method);
        if ('post' == $method) {
            curl_setopt($ch, CURLOPT_POST, true);
            if (is_array($fields)) {
                $sets = array();
                foreach ($fields AS $key => $val) {
                    $sets[] = $key . '=' . urlencode($val);
                }
                $fields = implode('&', $sets);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        } else if ('put' == $method) {
            curl_setopt($ch, CURLOPT_PUT, true);
        }
        //curl_setopt($ch, CURLOPT_PROGRESS, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        //curl_setopt($ch, CURLOPT_MUTE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //设置curl超时秒数
        if (strlen($userAgent)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }
        if (is_array($httpHeaders)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        $ret = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            //超时返回信息
            return '{"Qid":"AE40004","Err":-1,"Counter":1,"Data":{"code":"AE40004","desc":"Sorry, the network is delayed, please try again later"}}';
            //return array(curl_error($ch), curl_errno($ch));
        } else {
            curl_close($ch);
            if (!is_string($ret) || !strlen($ret)) {
                return false;
            }
            return $ret;
        }
    }

    /**
     * curl支持 检测
     * @return boolean | resource
     */
    public function create() {
        $ch = null;
        if (!function_exists('curl_init')) {
            return false;
        }
        $ch = curl_init();
        if (!is_resource($ch)) {
            return false;
        }
        return $ch;
    }
}

