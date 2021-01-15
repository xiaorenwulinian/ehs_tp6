<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\UploadConstant;
use app\common\library\oss\OssLib;


/**
 * @descption 公用的下拉框
 * Class CommonSelectBox
 * @package app\api\controller\v1
 */
class File extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();

    }

    public function upload()
    {

        $reqParam   = $this->request->param();
        $secondDir  = $reqParam['second_dir'] ?? 'common';
        $fileType   = $reqParam['file_type'] ?? 2;  // 1 是文件，2 是图片
        $hasThumb   = $reqParam['has_thumb'] ?? 2;  //  1。生成缩略图，其他不生成
        $thumbSizes = $reqParam['thumb_sizes'] ?? ''; //  1。压缩图片尺寸 长，款#长，宽 200,200#800,400
        // Log::error('params:' . json_encode($reqParam, JSON_UNESCAPED_UNICODE));

        $file = request()->file('file');

        if (!$file) {
            return api_failed('上传文件');
            // Log::error('上传文件失败:line43');
        }

        $allowImgArr = UploadConstant::UPLOAD_ALLOW_IMG_EXT;
        $allowImg = implode(',', $allowImgArr);

        try {

            $file->validate([
                'size' => 50 * 1024 * 1024,
//                'type' => $allowImg,
            ]);

            $threeDir = $fileType == 2 ? 'images' : 'files';

            $publicPath = ROOT_PATH . 'public';
            $dirPath    =  $publicPath . "/uploads/{$secondDir}/{$threeDir}/";
            $info       = $file->move($dirPath);

            if ($info) {

                $saveName =  $info->getSaveName();

                $absolutePath = $dirPath . $saveName;
                $dot          = strrpos($absolutePath,'.');
                $namePath     = substr($absolutePath,0, $dot);
                $extType       = substr($absolutePath,$dot);

                if ($fileType == 2 && $hasThumb == 1) {
                    $thumbArr = [];
                    if (!empty($thumbSizes)) {
                        $thumbArr = explode('#', $thumbSizes);
                    }

                    $i  = 0;
                    foreach ($thumbArr as $thumb) {
                        if (false === stristr($thumb, ',')) {
                            continue;
                        }
                        list($width, $height) = explode(',', $thumb);
                        $thumbName = $namePath . "_thumb_{$width}" . $extType ;

                        $image = \think\Image::open($absolutePath);
                        $image->thumb($width, $height)->save($thumbName);

                        $i++;
                    }
                }

                $saveName = str_replace("\\",'/', $saveName);

                $domain   = $this->request->domain();
                $filePath = "uploads/{$secondDir}/{$threeDir}/" . $saveName;
                $domainFilePath = $domain . '/'. $filePath;

            } else {
                throw new \Exception($file->getError());
            }

            $ret = [
                'file_path'        => $domainFilePath,
                'domain_file_path' => $filePath,

            ];
        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }
        return api_successed($ret);


    }


    public function uploadVideo()
    {

        $reqParam   = $this->request->param();
        $secondDir  = $reqParam['second_dir'] ?? 'common';
        $fileType   = $reqParam['file_type'] ?? 1;  // 1 是文件，2 是图片
        $hasThumb   = $reqParam['has_thumb'] ?? 2;  //  1。生成缩略图，其他不生成
        $thumbSizes = $reqParam['thumb_sizes'] ?? ''; //  1。压缩图片尺寸 长，款#长，宽 200,200#800,400

        $file = request()->file('file');
        if (!$file) {
            return api_failed('上传文件');
        }

        $allowImgArr = UploadConstant::UPLOAD_ALLOW_IMG_EXT;
        $allowImg = implode(',', $allowImgArr);

        try {

            $file->validate([
                'size' => 50 * 1024 * 1024,
//                'type' => $allowImg,
            ]);

            $threeDir = $fileType == 2 ? 'images' : 'files';

            $publicPath = ROOT_PATH . 'public';
            $dirPath    =  $publicPath . "/uploads/{$secondDir}/{$threeDir}/";
            $info       = $file->move($dirPath);

            if ($info) {

                $saveName =  $info->getSaveName();

                $absolutePath = $dirPath . $saveName;
                $dot          = strrpos($absolutePath,'.');
                $namePath     = substr($absolutePath,0, $dot);
                $extType       = substr($absolutePath,$dot);

                if ($fileType == 2 && $hasThumb == 1) {
                    $thumbArr = [];
                    if (!empty($thumbSizes)) {
                        $thumbArr = explode('#', $thumbSizes);
                    }

                    $i  = 0;
                    foreach ($thumbArr as $thumb) {
                        if (false === stristr($thumb, ',')) {
                            continue;
                        }
                        list($width, $height) = explode(',', $thumb);
                        $thumbName = $namePath . "_thumb_{$width}" . $extType ;

                        $image = \think\Image::open($absolutePath);
                        $image->thumb($width, $height)->save($thumbName);

                        $i++;
                    }
                }

                $saveName = str_replace("\\",'/', $saveName);

                $domain   = $this->request->domain();
                $filePath = "uploads/{$secondDir}/{$threeDir}/" . $saveName;
                $domainFilePath = $domain . '/'. $filePath;

            } else {
                throw new \Exception($file->getError());
            }

            $ret = [
                'file_path'        => $domainFilePath,
                'domain_file_path' => $filePath,

            ];
        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }
        return api_successed($ret);


    }

    public function del()
    {
        $string = request()->param('filename');
        $has = OssLib::getInstance()->deleteFile($string);
        dd($has);
    }

    public function exist()
    {
        $string = request()->param('filename');
        $has = OssLib::getInstance()->fileExist($string);
        dd($has);
    }

    public function test()
    {
        $reqParam   = $this->request->param();
        $secondDir  = $reqParam['second_dir'] ?? 'common';
        $fileType   = $reqParam['file_type'] ?? 1;  // 1 是文件，2 是图片
        $hasThumb   = $reqParam['has_thumb'] ?? 2;  //  1。生成缩略图，其他不生成
        $thumbSizes = $reqParam['thumb_sizes'] ?? ''; //  1。压缩图片尺寸 长，款#长，宽 200,200#800,400

        $file = request()->file('file');
        if (!$file) {
            return api_failed('上传文件');
        }

        $fileDir = $secondDir . '/' . 'images';

        $ret = OssLib::getInstance()->uploadFile($file, $fileDir);

        dd($ret);
        $fileInfo     = $file->getInfo();
        $tempPath     = $fileInfo['tmp_name'];
        $originalName = $fileInfo['name'];
        $originalArr  = explode('.', $originalName);
        $extension    = end($originalArr);

        dd($file);
        $allowImgArr = UploadConstant::UPLOAD_ALLOW_IMG_EXT;
        $allowImg = implode(',', $allowImgArr);

        try {

            $file->validate([
                'size' => 50 * 1024 * 1024,
//                'type' => $allowImg,
            ]);

            $threeDir = $fileType == 2 ? 'images' : 'files';

            $publicPath = ROOT_PATH . 'public';
            $dirPath    =  $publicPath . "/uploads/{$secondDir}/{$threeDir}/";
            $info       = $file->move($dirPath);

            if ($info) {

                $saveName =  $info->getSaveName();

                $absolutePath = $dirPath . $saveName;
                $dot          = strrpos($absolutePath,'.');
                $namePath     = substr($absolutePath,0, $dot);
                $extType       = substr($absolutePath,$dot);

                if ($fileType == 2 && $hasThumb == 1) {
                    $thumbArr = [];
                    if (!empty($thumbSizes)) {
                        $thumbArr = explode('#', $thumbSizes);
                    }

                    $i  = 0;
                    foreach ($thumbArr as $thumb) {
                        if (false === stristr($thumb, ',')) {
                            continue;
                        }
                        list($width, $height) = explode(',', $thumb);
                        $thumbName = $namePath . "_thumb_{$width}" . $extType ;

                        $image = \think\Image::open($absolutePath);
                        $image->thumb($width, $height)->save($thumbName);

                        $i++;
                    }
                }

                $saveName = str_replace("\\",'/', $saveName);

                $domain   = $this->request->domain();
                $filePath = "uploads/{$secondDir}/{$threeDir}/" . $saveName;
                $domainFilePath = $domain . '/'. $filePath;

            } else {
                throw new \Exception($file->getError());
            }

            $ret = [
                'file_path'        => $domainFilePath,
                'domain_file_path' => $filePath,

            ];
        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }
        return api_successed($ret);


    }

}
