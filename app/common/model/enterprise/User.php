<?php

namespace app\common\model\enterprise;


use think\Model;

class User extends Model
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

        /*self::beforeUpdate(function ($row) {
            $changedata = $row->getChangedData();
            if (isset($changedata['money'])) {
                $origin = $row->getOriginData();
                MoneyLog::create(['user_id' => $row['id'], 'money' => $changedata['money'] - $origin['money'], 'before' => $origin['money'], 'after' => $changedata['money'], 'memo' => '管理员变更金额']);
            }
            if (isset($changedata['score'])) {
                $origin = $row->getOriginData();
                ScoreLog::create(['user_id' => $row['id'], 'score' => $changedata['score'] - $origin['score'], 'before' => $origin['score'], 'after' => $changedata['score'], 'memo' => '管理员变更积分']);
            }
        });*/
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

        $domain = request()->domain();
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
