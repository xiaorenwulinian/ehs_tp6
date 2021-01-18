<?php

namespace app\common\model;

use app\common\constant\UploadConstant;
use think\Model;


class UserFollowPhotoModel extends Model
{
    // 表名
    protected $name = 'user_follow_photo';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名

    protected $append = [
        'domain_file'
    ];

    const AUDIT_STATUS_AUDIT_WAIT    = 11;
    const AUDIT_STATUS_AUDIT_PASS    = 21;
    const AUDIT_STATUS_AUDIT_FAILURE = 22;
    const AUDIT_STATUS_DEAL_SUCCESS  = 31;
    const AUDIT_STATUS_DEAL_FAILURE  = 32;

    const AUDIT_STATUS_ARR = [
        self::AUDIT_STATUS_AUDIT_WAIT => '待审核',
        self::AUDIT_STATUS_AUDIT_PASS => '处理中',
        self::AUDIT_STATUS_AUDIT_FAILURE => '已结案',
        self::AUDIT_STATUS_DEAL_SUCCESS => '已结案',
        self::AUDIT_STATUS_DEAL_FAILURE => '已结案',
    ];

    public function getDomainFileAttr($value, $data)
    {
        $image = '';

        if (!empty($data['files'])) {
            $arr = explode(',', $data['files']);

            $imgExtensionArr = UploadConstant::UPLOAD_ALLOW_IMG_EXT;

            foreach ($arr as $v) {
                list($filename, $ext) = explode('.', $v);
                $ext = strtolower($ext);
                if (in_array($ext, $imgExtensionArr)) {
                    $image = $v;
                    break;
                }
            }

        }

        if (empty($image)) {
            return '';
//            $image = UploadConstant::DEFAULT_IMAGE_PATH;
        }

        $domain = request()->domain();
        return $domain . '/' . $image;

    }


    public function setAuditRemarkAttr($value, $data)
    {
        return empty($value) ? '' : $value;
    }

    public function getAuditRemarkAttr($value)
    {
        return $value;
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'company_id','id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class,'proposer_id','id');
    }

    public function proposerUser()
    {
        return $this->belongsTo(UserModel::class,'proposer_id','id');
    }

    public function auditUser()
    {
        return $this->belongsTo(UserModel::class,'proposer_id','id');
    }


    public function companyArea()
    {
        return $this->belongsTo(CompanyAreaModel::class,'area_id','id');
    }


    public function riskLevel()
    {
       $level = [
           'A' => '红色',
           'B' => '橙色',
           'C' => '黄色',
           'D' => '蓝色'
       ];

       $degree = [
           1 => 'A',
           2 => 'B',
           3 => 'C',
           4 => 'D'
       ];
       $color = [
           1 => '红色',
           2 => '橙色',
           3 => '黄色',
           4 => '蓝色'
       ];

    }



}
