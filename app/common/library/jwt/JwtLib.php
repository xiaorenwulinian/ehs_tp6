<?php

namespace app\common\library\jwt;

class JwtLib
{
    /**
     * 支持的算法
     * @var array
     */
    public static $supported_algs = [
        'HS256' => ['hash_hmac', 'SHA256'],
    ];

    /**
     * 加密数据生成 token
     * @param $data
     * @param $secretKey
     * @param null $head
     * @param null $keyId
     * @param string $algorithm 加密算法
     * @return string 生成的token
     * @throws \Exception
     */
    public static function encrypt($data, $secretKey, $head = null, $keyId = null)
    {
        $header = [
            'type' => 'jwt',
            'alg'  => 'HS256'
        ];
        if (!is_null($keyId)) {
            $header['kid'] = $keyId;
        }
        if (!empty($head) && is_array($head)) {
            array_merge($header, $head);
        }
        $segmentArr = [
            static::urlSafeBase64Encode(static::jsonEncode($header)),
            static::urlSafeBase64Encode(static::jsonEncode($data))
        ];
        $signData = implode('.', $segmentArr);
        $signRet = static::makeSign($signData, $secretKey);
        if ($signRet['code'] !== 0) {
            throw new \Exception($signRet['msg']);
        }
        $signature = $signRet['data'];
        $segmentArr[] = static::urlSafeBase64Encode($signature);
        $token = implode('.',$segmentArr);
        return $token;

    }

    public static function jsonEncode($str)
    {
        $json = json_encode($str);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            $messages = array(
                JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
                JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
                JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
                JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
                JSON_ERROR_UTF8           => 'Malformed UTF-8 characters' //PHP >= 5.3.3
            );
            $msg = isset($messages[$errno]) ? $messages[$errno] : 'Unknown JSON error: ' . $errno;
            throw new \Exception($msg);
        } elseif ($json === 'null' && $str !== null) {
            throw new \Exception('Null result with non-null input');
        }
        return $json;
    }

    /**
     * 生成签名
     * @param string $data 要签名的数据
     * @param $secretKey  秘钥
     * @param string $alg 加密算法
     * @return array|string
     */
    public static function makeSign($data, $secretKey, $alg = 'HS256')
    {
        $ret = [
            'code' => 1, // 0 签名成功， 其余失败
            'msg'  => '',
            'data' => '' // 生成的签名
        ];
        list($function, $algorithm) = static::$supported_algs[$alg];
        $ret['code'] = 0;
        $signature = hash_hmac($algorithm, $data, $secretKey, true);
        $ret['data'] = $signature;
        return $ret;

    }

    /**
     * 将字符串转为 base64
     * @param $str
     * @return mixed
     */
    public static function urlSafeBase64Encode($str)
    {
        return str_replace('=', '', strtr(base64_encode($str), '+/', '-_'));
    }



    /**
     * 解密 token
     * @param $jwtToken 需要解密的 token 值
     * @param $secretKey 秘钥， 加密和解密时约定的密码
     * @param array $allowedAlgorithm
     * @return array
     * @throws \Exception
     */
    public static function decrypt($jwtToken, $secretKey, $allowedAlgorithm = ['HS256'])
    {
        $timestamp = time();
        $ret = [
            'code'    => 2,  // 0 解密成功，1.token 过期，2.其他
            'message' => '', // 异常返回信息
            'data'    => []  // 解密返回数据
        ];
        if (empty($secretKey)) {
            $ret['message'] = '秘钥不能为空！';
            return $ret;
        }
        $jwtTokenArr = explode('.', $jwtToken);
        if (count($jwtTokenArr) != 3) {
            $ret['message'] = '无效token,格式错误！';
            return $ret;
        }
        list($headerBase64, $bodyBase64, $cryptBase64) = $jwtTokenArr;
        try {
            if (null === ($header = static::jsonDecode(static::urlSafeBase64Decode($headerBase64)))) {
                $ret['message'] = '无效token,Invalid header encoding！';
                return $ret;
            }
            if (null === $payload = static::jsonDecode(static::urlSafeBase64Decode($bodyBase64))) {
                $ret['message'] = '无效token,Invalid claims encoding！';
                return $ret;
            }
        } catch (\Exception $e) {
            $ret['message'] = $e->getMessage();
            return $ret;
        }

        if (false === ($signature = static::urlSafeBase64Decode($cryptBase64))) {
            $ret['message'] = '无效token,Invalid signature encoding！';
            return $ret;
        }
        if (empty($header->alg)) {
            $ret['message'] = 'Empty algorithm';
            return $ret;
        }
        if (empty(static::$supported_algs[$header->alg])) {
            $ret['message'] = 'Algorithm not supported';
            return $ret;
        }
        if (!in_array($header->alg, $allowedAlgorithm)) {
            $ret['message'] = 'Algorithm not allowed';
            return $ret;
        }

        $headerBodyStr = $headerBase64 .'.'. $bodyBase64;
        if (!self::verifySign($headerBodyStr, $signature, $secretKey, $header->alg)) {
            $ret['message'] = '签名认证失败!';
            return $ret;
        }
        if (isset($payload->exp) && $timestamp > $payload->exp ) {
            $ret['message'] = 'token 过期!';
            $ret['code'] = 1;
            return $ret;
        }
        $ret['code'] = 0;
        $ret['data'] = $payload;
        return $ret;

    }


    /**
     * 验证签名是否合法
     * @param string $data   原始数据 （header + body ）
     * @param string $signature 签名
     * @param string | resource $key 秘钥或者公钥
     * @param string $alg 加密算法
     * @return bool 返回 true 通过验证
     */
    public static function verifySign($data, $signature, $key, $alg)
    {
        list($function, $algorithm)  = static::$supported_algs[$alg];
        $hash = hash_hmac($algorithm, $data, $key, true);
        if (function_exists('hash_equals')) {
            return hash_equals($signature, $hash);
        }
        $minLen = min(static::safeStringLength($hash), static::safeStringLength($signature));
        $status = 0;
        for ($i = 0; $i < $minLen ; $i++ ) {
            $status |= ( ord($signature[$i]) ^ ord($hash[$i]) );
        }
        return  $status === 0;

    }

    /**
     * 按位数 获取字符串的长度
     * @param $str
     * @return bool|int
     */
    public static function safeStringLength($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

    public static function jsonDecode($input)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $obj = json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
        } else {
            $max_int_length = strlen((string) PHP_INT_MAX) - 1;
            $json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
            $obj = json_decode($json_without_bigints);
        }

        if (function_exists('json_last_error') && $errno = json_last_error()) {
            $messages = array(
                JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
                JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
                JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
                JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
                JSON_ERROR_UTF8           => 'Malformed UTF-8 characters' //PHP >= 5.3.3
            );
            $msg = isset($messages[$errno]) ? $messages[$errno] : 'Unknown JSON error: ' . $errno;
            throw new \Exception($msg);
        } elseif ($obj === null && $input !== 'null') {
            throw new \Exception('Null result with non-null input');
        }
        return $obj;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function urlSafeBase64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

}
