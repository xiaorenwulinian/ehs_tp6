<?php

namespace app\common\constant;

class JobConstant
{
    /*
     * 岗位角色标签
     */
    const JOB_ROLE_LABEL_OBJ = [
       [
            'name'   => '一线作业人员',
            'desc'   => '一线作业人员/员工',
            'is_use' => 1, //  是否启用 1启用，2禁用
        ],
        [
            'name'   => '一线一级管理人员（直接管理作业人员）',
            'desc'   => '班组长/线长/车间主任/',
            'is_use' => 1,
        ],
        [
            'name'   => '一线二级管理人员（助理）',
            'desc'   => '维修/品管/实验室/仓库/物流/基础设施管理等部门负责人',
            'is_use' => 1,
        ],
        [
            'name'   => '保安员',
            'desc'   => '保安员',
            'is_use' => 1,
        ],
        [
            'name'   => '保安队长',
            'desc'   => '保安队长',
            'is_use' => 1,
        ],
        [
            'name'   => 'EHS总监',
            'desc'   => 'EHS总监',
            'is_use' => 1,
        ],
        [
            'name'   => 'EHS经理',
            'desc'   => 'EHS经理',
            'is_use' => 1,
        ],
        [
            'name'   => 'EHS专员',
            'desc'   => 'EHS专员',
            'is_use' => 1,
        ],
        [
            'name'   => '厂长经理（法律责任）',
            'desc'   => '厂长/总经理',
            'is_use' => 1,
        ],
        [
            'name'   => '二线作业人员',
            'desc'   => '助理/文员/商务/人事/财务等',
            'is_use' => 1,
        ],
        [
            'name'   => '二线管理人员',
            'desc'   => '职能部门负责人',
            'is_use' => 1,
        ],
        [
            'name'   => '员工代表/工会代表',
            'desc'   => '工会代表',
            'is_use' => 1,
        ],
        [
            'name'   => '管理者代表',
            'desc'   => '管理者代表',
            'is_use' => 1,
        ],
        [
            'name'   => '内审员',
            'desc'   => '内审员',
            'is_use' => 1,
        ],
        [
            'name'   => '文档管理员',
            'desc'   => '文档管理员',
            'is_use' => 1,
        ],
    ];


    /*
     * 区域权限配置
     */
    const AREA_AUTH_CONFIG_OBJ = [
//        1 => [
//            'id'   => 1,
//            'label' => '区域权限', // label ,显示的key
//            'name' => 'company_area_id', // 前端提交传的参数字段
//            'table_name' => 'company_area', // 表名
//            'identify' => 'company_area', // 标识
//            'type' => 2, // 2是下拉框
//            'span_size' => 12, // 前端布局的长度
//            'list' => [], // 下拉框的数据 id ,name
//        ],
        2 => [
            'id'   => 2,
            'label' => '设施', // label ,显示的key
            'name' => 'facility_id', // 前端提交传的参数字段
            'table_name' => 'facility', // 表名
            'identify' => 'facility', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],

        3 => [
            'id'   => 3,
            'label' => '摄像头', // label ,显示的key
            'name' => 'device_camera_id', // 前端提交传的参数字段
            'table_name' => 'device_camera', // 表名
            'identify' => 'device_camera', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],

        4 => [
            'id'   => 4,
            'label' => '上岗位', // label ,显示的key
            'name' => 'job_setting_id', // 前端提交传的参数字段
            'table_name' => 'job_setting', // 表名
            'identify' => 'job_setting', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
        5 => [
            'id'   => 5,
            'label' => '点检位', // label ,显示的key
            'name' => 'company_area_id', // 前端提交传的参数字段
            'table_name' => 'company_area', // 表名
            'identify' => 'company_area', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
        6 => [
            'id'   => 6,
            'label' => '巡检点', // label ,显示的key
            'name' => 'device_patrol_point_id', // 前端提交传的参数字段
            'table_name' => 'device_patrol_point', // 表名
            'identify' => 'device_patrol_point', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
        7 => [
            'id'   => 7,
            'label' => 'EHS监测点', // label ,显示的key
            'name' => 'ehs_point_id', // 前端提交传的参数字段
            'table_name' => 'ehs_point', // 表名
            'identify' => 'ehs_point', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
        8 => [
            'id'   => 8,
            'label' => '有限空间', // label ,显示的key
            'name' => 'device_limited_space_id', // 前端提交传的参数字段
            'table_name' => 'device_limited_space', // 表名
            'identify' => 'device_limited_space', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
        9 => [
            'id'   => 9,
            'label' => '定位点', // label ,显示的key
            'name' => 'device_location_point_id', // 前端提交传的参数字段
            'table_name' => 'device_location_point', // 表名
            'identify' => 'device_location_point', // 标识
            'type' => 2, // 2是下拉框
            'span_size' => 12, // 前端布局的长度
            'list' => [], // 下拉框的数据 id ,name
        ],
    ];

}
