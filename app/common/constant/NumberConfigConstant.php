<?php

namespace app\common\constant;

/**
 * @descption 自定义显示的表
 * Class IdentifyTableConstant
 * @package app\common\constant
 */
class NumberConfigConstant
{

    /*

区域编号设置	√ 开头字母(A)	√ 父级编号（选择）	√ 序号数字位数(2)
岗位编号设置	√ 开头字母(J)	√ 父级编号（选择）	√ 序号数字位数(2)
设施编号设置	√ 开头字母(SD)	区域编号（选择）	√ 序号数字位数(5)
人员编号设置	√ 开头字母(PER)	岗位编号（选择）	√ 序号数字位数(5)
摄像头编号设置	√ 开头字母(CAM)	区域编号（选择）	√ 序号数字位数(5)
定位点编号设置	√ 开头A字母(LOC)	区域编号（选择）	√ 序号数字位数(5)
手环编号设置	√ 开头字母(R)		√ 序号数字位数(5)
访客牌编号设置	√ 开头字母(V)		√ 序号数字位数(5)
点检点编号设置	√ 开头字母(SPOT)	设施编号（选择）	√ 序号数字位数(5)
安检点编号设置	√ 开头字母(SEC)	区域编号（选择）	√ 序号数字位数(5)
上岗点编号设置	√ 开头字母(DU)	区域编号（选择）	√ 序号数字位数(5)
身份证读卡器编号设置	√ 开头字母(RD)		√ 序号数字位数(5)
有限空间编号设置	√ 开头字母(SPACE)		√ 序号数字位数(5)
手环发卡器编号设置	√ 开头字母(SEND)		√ 序号数字位数(5)
EHS监测点编号设置	√ 开头字母(MON)	√ 区域	√ 序号数字位数(5)
申请单编号设置 	√ 开头字母(AP)	日期	√ 序号数字位数(5)
部门编号设置 	√ 开头字母(DEP)	√ 父级编号（选择）	√ 序号数字位数(5)

     */
    const AREA        = 1;
    const JOB         = 2;
    const FACILITY    = 3;
    const STAFF       = 4;
    const CAMERA      = 5;
    const LOCATION    = 6;
    const BANGLE      = 7;
    const VISITOR     = 8;
    const SPOT        = 9;
    const PATROL      = 10;
    const JOB_SETTING = 11;
    const IDENTIFY    = 12;
    const SPACE       = 13;
    const BRACELET    = 14;
    const MONITOR     = 15;
    const APPLY       = 16;
    const DEPARTMENT  = 17;
    const RFID        = 18;


    const NUMBER_ARR = [
      self::AREA            => 'company_area', // 区域编号设置
      self::JOB             => 'job', // 岗位编号设置
      self::FACILITY        => 'facility', // 设施编号设置
      self::STAFF           => 'staff', // 人员编号设置
      self::CAMERA          => 'device_camera', // 摄像头编号设置
      self::LOCATION        => 'device_location_point', // 定位点编号设置
      self::BANGLE          => 'device_bangle', // 手环编号编号设置
      self::VISITOR         => 'visitor', // 访客牌编号设置
      self::SPOT            => 'device_spot', // 点检点编号设置
      self::PATROL          => 'device_patrol_point', // 安检点编号设置
      self::JOB_SETTING     => 'job_setting', // 上岗点
      self::IDENTIFY        => 'device_identify_machine', // 身份证读卡器编号设置
      self::SPACE           => 'device_limited_space', // 有限空间编号设置
      self::BRACELET        => 'device_bracelet_machine', // 手环发卡器编号设置
      self::MONITOR         => 'ehs_point', // EHS监测点编号设置
      self::APPLY           => 'apply', // 申请单编号设置
      self::DEPARTMENT      => 'department', // 部门编号设置
      self::RFID            => 'device_rfid', // 部门编号设置
    ];

    const TABLE_NAME_ARR = [
        self::AREA          => 'company_area', // 区域编号设置
        self::JOB           => 'job', // 岗位编号设置
        self::FACILITY      => 'facility', // 设施编号设置
        self::STAFF         => 'staff', // 人员编号设置
        self::CAMERA        => 'device_camera', // 摄像头编号设置
        self::LOCATION      => 'device_location_point', // 定位点编号设置
        self::BANGLE        => 'device_bangle', // 手环编号编号设置
        self::VISITOR       => 'visitor', // 访客牌编号设置
        self::SPOT          => 'device_spot', // 点检点编号设置
        self::PATROL        => 'device_patrol_point', // 安检点编号设置
        self::JOB_SETTING   => 'job_setting', // 上岗点
        self::IDENTIFY      => 'device_identify_machine', // 身份证读卡器编号设置
        self::SPACE         => 'device_limited_space', // 有限空间编号设置
        self::BRACELET      => 'device_bracelet_machine', // 手环发卡器编号设置
        self::MONITOR       => 'ehs_point', // EHS监测点编号设置
        self::APPLY         => 'apply', // 申请单编号设置
        self::RFID          => 'device_rfid', // 部门编号设置
    ];


}
