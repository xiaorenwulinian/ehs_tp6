<?php

namespace app\common\constant;

/**
 * @descption 自定义显示的表
 * Class IdentifyTableConstant
 * @package app\common\constant
 */
class IdentifyTableConstant
{

   const USER       = 1;
   const ORDER      = 2;

    const ALL_MODULE = [
        self::USER      => 'user_index',  // 用户列表
        self::ORDER     => 'order_index', // 订单列表
    ];

    const ALL_MODULE_DESC = [
        self::USER      => '用户列表',
        self::ORDER     => '订单列表',
    ];


}
