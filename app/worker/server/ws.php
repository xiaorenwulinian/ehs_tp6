<?php

namespace app\worker\server;

use app\common\service\push\UserService;
use Channel\Client;

class ws extends MyBaseServer
{

    protected $socket = 'websocket://0.0.0.0:8801';
//ws://47.116.77.85:8801
//    protected $processes = 2; // 设置进程数
    protected $server_number; // 服务器编号

    protected $redis;       // redis-主库
    protected $redis_read;  // redis-从库

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct()
    {
        $this->redis = new \Redis();
        $redisConfig = [
            'host'      => '127.0.0.1',
            'port'      => 6379,
            'database'  => 0,
        ];
        $this->redis->pconnect($redisConfig['host'], $redisConfig['port']);
        $this->redis->select($redisConfig['database']);

        $this->option = [
            'count' => 4,
            'name' => 'ehs_server',
        ];

//        $this->server_number = \config('server_number', 1);
        $this->server_number = 1;

        $this->userService = new UserService();
        parent::__construct();
    }

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {

        if ($data != 'pong') {
            var_dump($data);
//            $a = json_decode($data, true);
//            $info = parse_url($data);
//            var_dump($a);
//            var_dump($info);
//            $connection->send('我收到你的信息了:' . $data);
//        foreach ($this->worker->connections as $k => $connection) {
////                $prefix = "k => {$k}, connect_id:" . $connection->id .'. ';
//            dump('say:' . $data);
//            $connection->send($data);
//        }
//        Client::connect('127.0.0.1', 2206);
            try {
                $messageData = json_decode($data, true);
                if (!$messageData || empty($messageData['type'])) {
//                    return ;
                }
                switch ($messageData['type']) {
                    case "user_bind":
                        $userId = $messageData['data']['user_id'];
                        /* $key = 'user:ws_u_cids:' . $userId;
                         $userConnectionIds = $this->redis->get($key);
                         $connectionIdInfo = $this->server_number . '-' . $this->worker->id . '-' . $connection->id;
                         $userConnectionIdArr = [];
                         if ($userConnectionIds) {
                             $userConnectionIdArr = unserialize($userConnectionIds);
                         }
                         array_push($userConnectionIdArr, $connectionIdInfo);
                         $this->redis->set('user:ws_c_uid:'. $connectionIdInfo, $userId);
                         $this->redis->set($key, serialize($userConnectionIdArr));*/

                        $this->userService->userBindConnection($userId,$this->server_number,$this->worker->id,$connection->id);
                        return ;
                        break;
                    case  "user_say" :
                        Client::publish('user_say',$messageData['data']['message']);
                        return;
                    case "navigator_info":
                        return;

                }

            } catch (\Exception $e) {
                echo "connection_id:{$connection->id},error: {$e->getMessage()}";
            }
            Client::publish('say',$data);
        }

    }


    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

        dump($this->server_number);
        dump($this->worker->id);
        dump($connection->id);
        $msg = "server_num:[{$this->server_number}],workerID:{$this->worker->id} connectionID:{$connection->id} connected\n";
        echo $msg;

        $ret = [
            'type' => 'init',
            'data' => [],
        ];
        $connection->send(json_encode($ret));
//        $connection->send("Successful connection [{$this->server_number}], workerID:{$this->worker->id}，connectionID:" . $connection->id);

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        $this->userService->userClose($this->server_number,$this->worker->id,$connection->id);
        echo  "on_close:server_num:[{$this->server_number}],workerID:{$this->worker->id} connectionID:{$connection->id} \n";
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param \Workerman\Worker $worker
     */
    public function onWorkerStart($worker)
    {
//        dump($worker);
        // Channel客户端连接到Channel服务端
        Client::connect('127.0.0.1', 2206);

        // 以自己的进程id为事件名称
        $event_name = $worker->id;
        dump('event_name:'. $event_name);
        Client::on($event_name, function ($event_data) use ($worker) {
            $connection_id = $event_data['to_connection_id'] ?? '';
            dump('===connct_id:'. $connection_id);
            if (!isset($worker->connections[$connection_id])) {
                echo "connect not exist \n";
                return ;
            }
            $msg = $event_data['content'] ?? 'c';
            dump('==content:=='. $msg );
            $to_connection = $worker->connections[$connection_id];
            $prefix = "k => {$connection_id} \n";
            echo $prefix .  $msg;
//            $to_connection->send($prefix .  $msg);
            $ret = [
                'type' => 'text',
                'data' => [
                    'msg' => $msg
                ],
            ];
            $to_connection->send(json_encode($ret));
        });



        Client::on('广播', function ($event_data) use ($worker) {
            $msg = $event_data['content'] ?? 'c';
            dump('==content:==', $msg );
            foreach ($worker->connections as $k => $connection) {
                $prefix = "k => {$k}, connect_id:" . $connection->id .'. ';
//                $connection->send($prefix .  $msg);

                $ret = [
                    'type' => 'text',
                    'data' => [
                        'msg' => $msg
                    ],
                ];
                $connection->send(json_encode($ret));
            }
        });

        Client::on('user_say', function ($event_data) use ($worker) {
            dump('say:===' );
            foreach ($worker->connections as $k => $connection) {
//                $prefix = "k => {$k}, connect_id:" . $connection->id .'. ';
                dump('say:' . $event_data);
                $ret = [
                    'type' => 'text',
                    'data' => [
                        'msg' => $event_data
                    ],
                ];
                $connection->send(json_encode($ret));
            }
        });

        Client::on('navigate_bar_info:' . $event_name, function ($event_data) use ($worker) {
            $connection_id = $event_data['to_connection_id'] ?? '';
            dump('===connct_id:'. $connection_id);
            if (!isset($worker->connections[$connection_id])) {
                echo "connect not exist \n";
                return ;
            }
            $msg = $event_data['content'] ?? 'c';
            dump('==content:=='. $msg );
            $to_connection = $worker->connections[$connection_id];
            $prefix = "k => {$connection_id} \n";
            echo $prefix .  $msg;
//            $to_connection->send($prefix .  $msg);
            $ret = [
                'type' => 'navigate_bar_info',
                'data' => [
                    'msg' => $msg
                ],
            ];
            $to_connection->send(json_encode($ret));
        });
    }
}