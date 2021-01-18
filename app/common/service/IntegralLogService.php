<?php

namespace app\common\service;

use app\common\constant\IntegralConstant;
use app\common\model\UserModel;
use think\facade\Db;
use think\facade\Log;


/**
 *
 * Class JobAbilityService
 * @package app\common\service
 */
class IntegralLogService
{
    /**
     * 积分记录
     * @param $userId
     * @param $type
     * @param $num
     * @return bool
     */
    public function log($userId, $type, $num)
    {
        Db::startTrans();

        try {
            $user = UserModel::find($userId);
            $beforeIntegral = $user->score;

            if (in_array($type, IntegralConstant::REWARD_ALL)) {
                $awardType = 1;
                $newIntegral = $beforeIntegral + $num;
            } else {
                $awardType = 2;
                $newIntegral = $beforeIntegral - $num;

            }

            $time = date('Y-m-d H:i:s');

            $data = [
                'user_id'               => $userId,
                'integral_type'         => $type,
                'integral_num'          => $num,
                'is_award'              => $awardType,
                'before_integral_num'   => $beforeIntegral,
                'award_time'            => $time,
                'company_id'            => $user->company_id,
            ];
            Db::name('user_integral_log')->insert($data);

            $user->score = $newIntegral;
            $user->save();

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Log::info("integral_log_error:{$e->getMessage()} ,params:" . json_decode(func_get_args(), 256));
            return false;
        }

        return true;

    }

    /**
     * 用户签到
     * @param int $userId
     * @return bool
     */
    public function userSign(int $userId): bool
    {
        $type = IntegralConstant::INTEGRAL_SIGN;
        $num = 1;
        return $this->log($userId, $type, $num);
    }

    /**
     * 随拍审核通过
     * @param int $userId
     * @param int $num
     * @return bool
     */
    public function followPhoto(int $userId) : bool
    {
        $num = 1;

        $type = IntegralConstant::INTEGRAL_PHOTO;
        return $this->log($userId, $type, $num);
    }

}