<?php
namespace app\common\library\oss;

use OSS\OssClient;
use think\Env;

class OssLib
{

    private static $instance = null;
    private $ossClient = null;


    public $accessKeyId;
    public $accessKeySecret;
    public $endpoint;
    public $bucket;
    public $maxSize;

    public function __construct()
    {

        $this->accessKeyId     = Env::get('alioss.access_key_id','');
        $this->accessKeySecret = Env::get('alioss.access_key_secret','');
        $this->endpoint        = Env::get('alioss.endpoint','');
        $this->bucket          = Env::get('alioss.bucket','');
        $this->maxSize         = Env::get('alioss.maxsize', 100 * 1024 * 1024);


        $this->ossClient = new \OSS\OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);

        // 判断 bucketname 是否存在，不存在创建
        if (!$this->ossClient->doesBucketExist($this->bucket)) {
            $this->ossClient->createBucket($this->bucket);
        }
    }

    /**
     * 文件单一入口
     * @return OssLib|null
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __clone() {}

    /**
     * 文件上传
     * @param $file
     * @param string $dir
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function uploadFile($file, $dir = '')
    {

        $fileInfo     = $file->getInfo();

        $tempPath     = $fileInfo['tmp_name'];
        $originalName = $fileInfo['name'];
        $size         = $fileInfo['size'];
        $originalArr  = explode('.', $originalName);
        $extension    = end($originalArr);


        if ($size > $this->maxSize) {
            $max_size_m = ceil($this->maxSize/1024/1024);
            throw new \Exception("上传文件大小超出限制，最大允许上传 {$max_size_m} M");
        }


        $filename = $this->getFilenameMd5() . '.'. $extension;
        $dir = trim(str_replace('\\','/', $dir),'/');
        if (!empty($dir)) {
            $dir .= '/';
        }
        $dir .= date("Ymd") . '/';
        $object = $filePathName = $dir . $filename;  // 拼接文件夹和文件名
        $this->ossClient->uploadFile($this->bucket, $object, $tempPath); // oss 上传方式 1

        $ret = [
            'originalName'    => $originalName,
            'ossRelativePath' => $object,
            'ossAbsolutePath' => $this->signUrl($object),
        ];

        return $ret;
    }

    /**
     * 删除文件
     * @param $path
     * @return bool
     */
    public function deleteFile($path)
    {
        if ($this->fileExist($path)) {
            $this->ossClient->deleteObject($this->bucket, $path);
        }
        return true;
    }

    /**
     * 判断文件是否存在
     * @param $path
     * @return bool
     */
    public function fileExist($path)
    {
        if ($this->ossClient->doesObjectExist($this->bucket, $path)) {
            return true;
        }
        return false;
    }

    /**
     * oss 可访问文件绝对路径
     * @param int $timeout oss可访问文件有效时长，默认 1 小时
     * @throws \OSS\Core\OssException
     */
    public function signUrl($object, $timeout = 3600)
    {
        if ($this->fileExist($object)) {
            return $this->ossClient->signUrl($this->bucket, $object, $timeout);
        }
        return  '';
    }

    /**
     * MD5 加密生成唯一的文件名
     * @return string
     */
    private function getFilenameMd5()
    {
        return md5(microtime(true). uniqid() . rand(100000,999999))  ;
    }

}


