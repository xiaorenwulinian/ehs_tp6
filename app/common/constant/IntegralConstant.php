<?php

namespace app\common\constant;

/**
 * 积分
 * Class IntegralConstant
 * @package app\common\constant
 */
class IntegralConstant
{
    const USER_DEGREE_ARR = [
        [
            'name'          => '生手',
            'min_level'     => 1,
            'max_level'     => 30,
            'desc'          => '1-30级',
            'upgrade_score' => 100,
        ],
        [
            'name'          => '熟手',
            'min_level'     => 31,
            'max_level'     => 55,
            'desc'          => '31-55级',
            'upgrade_score' => '200',
        ],
        [
            'name'          => '高手',
            'min_level'     => 56,
            'max_level'     => 80,
            'desc'          => '56-80级',
            'upgrade_score' => 300,
        ],
        [
            'name'          => '教练',
            'min_level'     => 81,
            'max_level'     => 90,
            'desc'          => '81-90级',
            'upgrade_score' => 200,
        ],
        [
            'name'          => '大师',
            'min_level'     => 91,
            'max_level'     => 100,
            'desc'          => '91-100级',
            'upgrade_score' => 500,
        ],
        [
            'name'          => '终身大师',
            'min_level'     => 101,
            'max_level'     => 9999,
            'desc'          => '100级以上',
            'upgrade_score' => 500,
        ],
    ];

   const INTEGRAL_SIGN  = 101;
   const INTEGRAL_PHOTO = 201;
   const INTEGRAL_SAFETY = 202;
   const INTEGRAL_POINT_CHECK = 301;

   /*
    * 积分所有类型
    */
   const INTEGRAL_TYPE_ALL = [
       self::INTEGRAL_SIGN  => '签到',
       self::INTEGRAL_PHOTO => '随拍',
       self::INTEGRAL_SAFETY => '安全天数',
       self::INTEGRAL_POINT_CHECK => '点检',

   ];

   const INTEGRAL_REGULAR_OBJ = [
       self::INTEGRAL_PHOTO  =>  [
           'type_id'        => self::INTEGRAL_PHOTO,
           'type_name'      => '随拍',
//           'rule_desc'   => '随拍后得到确认，每次添加相应的分数',
           'rule_desc'   => '随拍后得到确认，每次+#score#分',
           'score'          =>  30,
           'is_reward'      =>  1,
       ],
       self::INTEGRAL_SAFETY  =>  [
           'type_id'        => self::INTEGRAL_SAFETY,
           'type_name'      => '安全天数',
           'rule_desc'   => '安全后得到确认，每天+#score#分',
           'score'          =>  1,
           'is_reward'      =>  1,
       ],

   ];

   const REWARD_ALL = [
       self::INTEGRAL_SIGN,
       self::INTEGRAL_PHOTO,
       self::INTEGRAL_POINT_CHECK,
   ];


    /**
     * 任务类型
     */
    const TASK_TYPE_POINT_CHECK = 1;
    const TASK_TYPE_INSPECT     = 2;
    const TASK_TYPE_STUDY       = 3;
    const TASK_TYPE_CHANGE      = 4;
    const TASK_TYPE_AUDIT       = 5;
    const TASK_TYPE_THREE_CHECK = 6;
    const TASK_TYPE_OD_CHECK    = 7;
    const TASK_TYPE_QUALIFICATION_UPDATE = 8;

    const TASK_TYPE_ARR = [
        self::TASK_TYPE_POINT_CHECK            => '点检',
        self::TASK_TYPE_INSPECT                => '检查',
        self::TASK_TYPE_STUDY                  => '学习',
        self::TASK_TYPE_CHANGE                 => '整改/改进',
        self::TASK_TYPE_AUDIT                  => '审核',
        self::TASK_TYPE_THREE_CHECK            => '第三方检测',
        self::TASK_TYPE_OD_CHECK               => '职业病体检',
        self::TASK_TYPE_QUALIFICATION_UPDATE   => '资质更新',
    ];



}
