<?php

namespace app\common\model\enterprise;

use think\Model;


class DeviceRfid extends Model
{

    /**
    CREATE TABLE `t_rfid_device` (
    `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `device_no` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '设备编号',
    `name` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '设备名称 (自定义)',
    `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型  1:主体机  2:组合机',
    `scene` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用场景  1:点检点 2:定位点  3:巡检点(安检点)',
    `ip` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'IP地址',
    `identify_code` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '识别码',
    `line_code` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '天线码',
    `company_id` int NOT NULL COMMENT '公司ID',
    `is_deleted` tinyint(1) NOT NULL COMMENT '是否删除，1是，0否',
    `ctime` int NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='RFID设备表';
     */

    /**
     * 使用场景
     */
    const SCENE_POINT    = 1;
    const SCENE_LOCATION = 2;
    const SCENE_PATROL   = 3;

    const SCENE_ARR = [
        self::SCENE_POINT    => '点检',
        self::SCENE_LOCATION => '定位',
        self::SCENE_PATROL   => '巡检',
    ];

    /**
     * 类型
     */
    const TYPE_ENTITY  = 1;
    const TYPE_COMBINE = 2;

    const TYPE_ARR = [
        self::TYPE_ENTITY    => '一体机',
        self::TYPE_COMBINE   => '组合机',
    ];

    // 表名
    protected $name = 'device_rfid';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = false;
    protected $deleteTime = false;

    protected $append = [
        'scene_str',
        'type_str',
    ];
    public function getCtimeAttr($value)
    {
        if (!empty($value)) {
            return !is_numeric($value) ? $value : date("Y-m-d H:i:s", $value);
        } else {
            return '';
        }
    }


    public function getSceneStrAttr($value, $data)
    {
        return self::SCENE_ARR[$data['scene']] ?? '';
    }

    public function getTypeStrAttr($value, $data)
    {
        return self::TYPE_ARR[$data['type']] ?? '';
    }



}
