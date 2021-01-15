<?php

namespace app\common\constant;

class WorkConstant
{
    const WORK_HIGH = 1;
    const WORK_FIRE = 2;
    const WORK_DIRT = 3;
    const WORK_ELECTRIC = 4;
    const WORK_LIMIT_SPARE = 5;
    const WORK_SLING = 6;
    const WORK_CUTTING_OUT = 7;
    const WORK_BLIND = 8;


    const WORK_TYPE_ARR = [
        self::WORK_HIGH => '高处作业',
        self::WORK_FIRE => '动火作业',
        self::WORK_DIRT => '动土作业',
        self::WORK_ELECTRIC => '临时用电作业',
        self::WORK_LIMIT_SPARE => '有限空间作业',
        self::WORK_SLING => '吊装作业',
        self::WORK_CUTTING_OUT => '断路作业',
        self::WORK_BLIND => '盲板抽堵作业',
    ];

    const WORK_TYPE_OBJ = [
        self::WORK_HIGH => [
            'id' => self::WORK_HIGH,
            'name' => '高处作业',
        ],
        self::WORK_FIRE => '动火作业',
    ];
}
