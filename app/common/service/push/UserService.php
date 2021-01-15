<?php

namespace app\common\service\push;

use think\facade\Db;

class UserService extends PushBaseService
{


    /**
     * 用户绑定ws connection_id
     * @param $userId
     * @param $serverNum
     * @param $workerId
     * @param $connectionId
     * @return bool
     */
    public function userBindConnection($userId, $serverNum, $workerId, $connectionId)
    {
        echo 'userBindConnection:'. $userId . '=' . $serverNum . '-'.$workerId . "\n";
        try {
            Db::name('user_ws_connection')->insert([
                'user_id'       => $userId,
                'server_num'    => $serverNum,
                'worker_id'     => $workerId,
                'connection_id' => $connectionId,
                'addtime'       => date("Y-m-d H:i:s"),
            ]);
        } catch (\Exception $e) {
            echo "userBindConnection:error:" . $e->getMessage();
        }

        return true;
    }

    public function userClose($serverNum, $workerId, $connectionId)
    {

        echo 'userClose:'. $serverNum . '-'.$workerId . "\n";
        try {
            Db::name('user_ws_connection')->where([
                'server_num'    => $serverNum,
                'worker_id'     => $workerId,
                'connection_id' => $connectionId,
            ])->update([
                'updatetime' => date("Y-m-d H:i:s"),
                'is_delete'  => 2,
            ]);
        } catch (\Exception $e) {
            echo "userBindConnection:error:" . $e->getMessage();
        }

        return true;
    }
}

