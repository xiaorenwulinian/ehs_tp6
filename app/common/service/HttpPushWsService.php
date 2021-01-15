<?php

namespace app\common\service;




use app\push\service\PushBaseService;
use think\facade\Db;
use think\facade\Log;

/**
 *
 * Class HttpPushWsService
 * @package app\common\service
 */
class HttpPushWsService {

    public function send($params)
    {
        try {
          $userId = $params['user_id'] ?? '';
          $content = $params['content'] ?? '';
          if ($content) {
              $domain = "http://47.116.77.85:8802";
              if ($userId) {
                  $ws_connection = Db::name('user_ws_connection')->where([
                      'user_id'    => $userId,
                      'is_delete'  => 1,
                  ])->select();

                  foreach ($ws_connection as $ws) {
                      $route = "?type=1&content={$content}&to_connection_id={$ws['connection_id']}&to_worker_id={$ws['worker_id']}";
                      (new PushBaseService())->get($domain . $route);
                  }

              } else {
                  $route = "?type=1&content={$content}";
                  (new PushBaseService())->get($domain . $route);
              }

          }
        } catch (\Exception $e) {
          Log::info("ws_send:error:". $e->getMessage());
          return false;
        }


        return true;

    }

    public function monitorSend($params)
    {

        try {
            $userId = $params['user_id'] ?? '';
            $content = $params['content'] ?? '';
            if ($content) {
                $domain = "http://47.116.77.85:8802";
                if ($userId) {
                    $ws_connection = Db::name('user_ws_connection')->where([
                        'user_id'    => $userId,
                        'is_delete'  => 1,
                    ])->select();

                    foreach ($ws_connection as $ws) {
                        $route = "?type=1&content={$content}&to_connection_id={$ws['connection_id']}&to_worker_id={$ws['worker_id']}";
                        (new PushBaseService())->get($domain . $route);
                    }

                } else {
                    $route = "?type=1&content={$content}";
                    (new PushBaseService())->get($domain . $route);
                }

            }
        } catch (\Exception $e) {
            Log::info("ws_send:error:". $e->getMessage());
            return false;
        }


        return true;

    }


}