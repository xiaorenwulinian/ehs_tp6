<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\service\HttpPushWsService;

use app\common\library\StringLib;
use think\Db;
use think\Log;

/**
 * 第三方对接
 */
class Hardware extends ApiBase
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];



    public function _initialize()
    {
        parent::_initialize();

    }


    /**
     * 昏倒报警
     */
    public function faintWarning()
    {

        /*



        消防通道阻塞
    相隔P1分钟的图片对比
    触发条件：差异静止P2分钟。

烟雾侦测（蒸汽和烟气）
    相隔P1分钟的局部（方格水平有P2个，垂直有P3个）
    灰度相对强度的变化超过了P4%

车辆超速行驶
    速度槛P1
    P1时，
        对象从左到右的时间P2
        对象从上到下的时间P3

物体异常（色/温/位/角）
    请设置更多场景化条件。
    温度需要热成像仪，测量具体的温度分布。

受限空间安全状态检查
    请提出具体要求

指针/数字表盘参数异常
    请提供摄像头静止的录像。
    将提示发现的N个表盘。
    需要标记哪些个表P1
    是否线性P2
    非线性对照表P3，或线性的倍率P4
    确认接口形式为json字符形式。



        目前阶段，先做下面的。下面的是意见稿。
昏倒报警（在定义的范围内、发现有物体静止）
    Conf0 = { # 以下接口共有参数：
        v_fn: 'rtsp strg', # 摄像头地址P01
        star_time_week: 'Monday', # 起讫时间特征
        end_time_week: 'Friday', # 起讫时间特征
        star_time_day: '0800', # 起讫时间特征
        end_time_day: '1700', # 起讫时间特征
        range:[(0,0),(600,600)], # 范围为矩形区域，它的左上角和其对角。
        time_span_report_good: 1000, # 反馈正常的时间周期秒
        }
    ConfFaint = { 'Conf0': Conf0
        p: 15, # 多少时间静止为触发3级报警的条件
        p: 25, # 多少时间静止为触发2级报警的条件
        p: 45, # 多少时间静止为触发1级报警的条件
        range_io:[[(0,0),(2,2)], [(3,3),(9,29)],], } # 出口和入口
    Event = {
        id = 'faint.3.0001'
        imfn: '192.168.1.1//events/img/11.jpg',}

         */
        $params = $this->request->param();

        $v_fn = $params['v_fn'] ?? ''; // 摄像头地址

    }

    /**
     * @descption 安防-报警设置
     */
    public function safetyConfig()
    {
        $params = $this->request->param();
        $id = $params['device_monitor_id'] ?? '';
        if (empty($id)) {
            return api_failed("设备id必须");
        }

        $field = [
//            'safety_config_id',
//            'company_device_monitor_id',
//            'name',
            'static_num',
            'warn_num',
        ];
        $data = Db::name('safety_config')
            ->where('company_device_monitor_id', $id)
            ->field($field)
            ->find();

        if (!$data) {
            return api_failed('未发现该数据');
        }

        $ret = [
            'info' => $data
        ];

        return api_successed($ret);
    }

    private function mockData()
    {
        $sleep = time() - rand(10000,99999);
        $wake = time() - rand(1000,9999);
        $gap = $wake - $sleep;
        $str = [
            'company_device_monitor_id' => rand(1,3),
            'name'                      => StringLib::random(6,4),
            'desc'                      => StringLib::random(16,4),
            'atime'                     => time(),
            'sleep_time'                => $sleep,
            'wake_time'                 => $wake,
            'sleep_num'                 => $gap,
            'images'                    => StringLib::random(16,0),
        ];

        $str = [
            'content' => $str
        ];
        return $str;

        $json = json_encode($str, 256);


        dd($str, $json);
    }

    private function validateSign($params)
    {

    }

    /**
     * @descption 安防报警记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function safetyRecordWarningAdd()
    {
        // $content = $this->mockData();
        $content = request()->param();
        // $content = file_get_contents('php://input');
        try {
            Log::info('params:' . json_encode(compact('content'),256));
//            $data = $content['data']['evnt_json'];
            $data = $content['data'];
            $timestamp = $content['timestamp'];
            $sign = $content['sign'];
            $slat = 'ehs';

            $makeSign = md5(md5($slat . $timestamp));
            if ($makeSign != $sign) {
               throw new \Exception("签名验证失败");
            }

//            1摔倒, 2入睡, 3消防通道阻塞, 4表针,5多人作业
            $typeArr = [
                1 => 'trip',
                2 => 'sleep',
                3 => 'fire_access_blocked',
                4 => 'watch_hand',
                5 => 'multi_person_operation',
            ];
            if (!in_array($data['warning_type'], $typeArr)) {
                throw new \Exception("未发现该行为类型");
            }
            $typeKeys = array_flip($typeArr);

            $type = $typeKeys[$data['warning_type']];

            switch ($type) {
                case 5 :
                    $this->log5($data, $type);
                    break;
            }

            $ret = [
                'data' => $data,
            ];

        } catch (\Exception $e) {
            $msg = "safetyRecordWarning_receipt_error:{$e->getMessage()} ,params:";
            Log::error($msg . json_encode(compact('content'),256));

            return json(result_failed($e->getMessage()));

        }
        return json(result_successed($ret));

    }

    private function log5($data, $type)
    {
//        try {

            $device_id = $data['device_id'];
            $images    = $data['images'] ?? '';
            $md5_str   = $data['md5_str'];
            $tableName = 'safety_record_warning_' . $type;

            $camara = Db::name('device_camera')
                ->where('id', $device_id)
                ->find();
            if (!$camara) {
                throw new \Exception("未发现该摄像头");
            }

            $userIds = Db::name('company_area')
                ->where('id',$camara['company_area_id'])
                ->value('director_id');


            $name = $data['name'] ?? '检测到单人作业行为';
            $newId =  Db::name('safety_record_warning')->insertGetId([
                'type'                      => $type,
                'name'                      => $name,
                'atime'                     => $data['atime'] ?? time(),
                'company_device_monitor_id' => $camara['id'],
                'images'                    => $images,
                'company_id'                => $monitor['company_id'] ?? 0,
                'company_area_id'           => $monitor['company_area_id'] ?? '',
                'ctime'                     => time(),
                'user_ids'                  => $userIds,
                'md5_str'                   => $md5_str,
            ]);

            Db::name($tableName)->insertGetId([
                'company_id'                => $camara['company_id'],
                'company_area_id'           => $camara['company_area_id'],
                'company_device_monitor_id' => $camara['id'],
                'user_ids'                  => $userIds,
                'job_ids'                   => '',
                'name'                      => $name,
                'atime'                     => $data['atime'] ?? time(),
                'images'                    => $images,
                'safety_record_warning_id'  => $newId,
            ]);

            Db::name('safety_record_warning_read')->insertGetId([
                'company_id'                => $camara['company_id'],
                'user_id'                   => $userIds,
                'ctime'                     => $data['atime'] ?? time(),
                'safety_record_warning_id'  => $newId,
            ]);

            (new HttpPushWsService())->monitorSend([
                'content' => $name,
//                        'user_id' => $userIds,
            ]);
//        } catch (\Exception $e) {
//            $msg = "safetyRecordWarningAdd_error:{$e->getMessage()} ,params:";
//            Log::error($msg . json_encode(compact('data'),256));
//            return false;
//        }

        return true;


    }

    //根据设备编号获取设备数据(摄像头)
    public function get_device_data_by_machine_no()
    {
        $content = request()->param();

        $data = $content['data'];
        $machine_no = $data['machine_no'];

        try {
            $row = Db::name('device_camera')->where('machine_no', $machine_no)->find();
        } catch (\Exception $e) {
            $msg = "safetyRecordWarning_receipt_error:{$e->getMessage()} ,params:";
            Log::error($msg . json_encode(compact('content'),256));
            return json(result_failed($e->getMessage()));
        }
        return json(result_successed($row));
    }

    //根据设备id获取设备数据(摄像头)
    public function get_device_by_id()
    {
        $content = request()->param();

        $data = $content['data'];
        $device_id = $data['device_id'];

        try {
            $row = Db::name('device_camera')->where('id', $device_id)->find();
        } catch (\Exception $e) {
            $msg = "safetyRecordWarning_receipt_error:{$e->getMessage()} ,params:";
            Log::error($msg . json_encode(compact('content'),256));
            return json(result_failed($e->getMessage()));
        }
        return json(result_successed($row));
    }

}