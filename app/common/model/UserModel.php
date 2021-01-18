<?php

namespace app\common\model;


use think\Model;

class UserModel extends Model
{

    // 表名
    protected $name = 'user';

    protected $pk = "id";

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected $append = [
        'domain_avatar',
        'sex_string',
    ];
/*
    public function getOriginData()
    {
        return $this->origin;
    }*/

    protected static function init()
    {

    }

    public function getGenderList()
    {
        return ['1' => __('Male'), '0' => __('Female')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function setPasswordAttr($value)
    {
        return md5(md5($value));

    }

    public function setNicknameAttr($value)
    {
        return is_null($value) ? '' : $value;
    }


    protected function setBirthdayAttr($value)
    {
        return $value ? $value : null;
    }


    public function getDomainAvatarAttr($value, $data)
    {
        if (empty($data['avatar'])) {
            return  '';
        }

        $domain = "http://file.shhka.com";
        return $domain . '/' . $data['avatar'];
    }

    public function getSexStringAttr($value, $data)
    {

        if (empty($data['sex'])) {
            return  '';
        }
        // 性别,1男，2女，3未知
        $arr = [
            1 => '男',
            2 => '女',
            3 => '未知',
        ];

        return  $arr[$data['sex']] ?? '';
    }

}
