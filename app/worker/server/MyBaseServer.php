<?php

namespace app\worker\server;

use Channel\Client;
use Workerman\Worker;

abstract class MyBaseServer
{

    protected $worker;
    protected $socket    = '';
    protected $protocol  = 'http';
    protected $host      = '0.0.0.0';
    protected $port      = '2346';
    protected $option   = [];
    protected $context  = [];
    protected $event    = ['onWorkerStart', 'onConnect', 'onMessage', 'onClose', 'onError', 'onBufferFull', 'onBufferDrain', 'onWorkerReload', 'onWebSocketConnect'];
    // tp5.0
    //    protected $event    = ['onWorkerStart', 'onConnect', 'onMessage', 'onClose', 'onError', 'onBufferFull', 'onBufferDrain', 'onWorkerStop', 'onWorkerReload'];

//    protected $processes = 4;

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        $channel_server = new \Channel\Server('127.0.0.1', 2206);

        // 实例化 Websocket 服务
//        $this->worker = new Worker($this->socket ?: $this->protocol . '://' . $this->host . ':' . $this->port);
        $this->worker = new Worker($this->socket ?: $this->protocol . '://' . $this->host . ':' . $this->port, $this->context);

        // 设置进程数
//        $this->worker->count = $this->processes;
        // 初始化
        $this->init();

        // 设置进程名称
//        $this->worker->name = 'ehs_server';


        // 设置参数
        if (!empty($this->option)) {
            foreach ($this->option as $key => $val) {
                $this->worker->$key = $val;
            }
        }

        // 设置回调
        foreach ($this->event as $event) {
            if (method_exists($this, $event)) {
                $this->worker->$event = [$this, $event];
            }
        }

        // 用来处理http请求，向任意客户端推送数据，需要传workerID和connectionID
        $this->httpServer();

        // Run worker
        Worker::runAll();
    }

    protected function init()
    {
    }

    public function httpServer()
    {
        $http_worker = new Worker('http://0.0.0.0:8802');
        $http_worker->name = 'publisher';
        $http_worker->onWorkerStart = function() {

            Client::connect('127.0.0.1', 2206);
//            Client::connect('47.116.77.85', 2206);
        };
        $http_worker->onMessage = function($connection, $data)
        {
            var_dump($data);
            $connection->send('ok');
            if (empty($_GET['type'])) {
                return;
            }
            $type = [
                1, // send message
                2, // push navigate num
            ];
            switch ($_GET['type']) {
                case 1:
                    if (empty($_GET['content'])) {
                        return;
                    }
                    // 是向某个worker进程中某个连接推送数据
                    if (isset($_GET['to_worker_id']) && isset($_GET['to_connection_id'])) {
                        $event_name = $_GET['to_worker_id'];
                        $to_connection_id = $_GET['to_connection_id'];
                        $content = $_GET['content'];
                        Client::publish($event_name, array(
                            'to_connection_id' => $to_connection_id,
                            'content'          => $content
                        ));
                    }
                    // 是全局广播数据
                    else {
                        $event_name = '广播';
                        $content = $_GET['content'];
                        Client::publish($event_name, array(
                            'content' => $content
                        ));
                    }
                    break;
                case 2:

                    $content = $_GET['content'] ?? '';
                    // 是向某个worker进程中某个连接推送数据
                    if (isset($_GET['to_worker_id']) && isset($_GET['to_connection_id'])) {
                        $event_name = $_GET['to_worker_id'];
                        $to_connection_id = $_GET['to_connection_id'];
                        Client::publish("navigate_bar_info:" . $event_name, array(
                            'to_connection_id' => $to_connection_id,
                            'content'          => $content
                        ));
                    }

            }

        };
    }

    public function __set($name, $value)
    {
        $this->worker->$name = $value;
    }

    public function __call($method, $args)
    {
        call_user_func_array([$this->worker, $method], $args);
    }

}