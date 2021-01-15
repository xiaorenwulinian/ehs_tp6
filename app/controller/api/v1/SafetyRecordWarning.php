<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\validate\SafetyConfigValidate;
use app\common\controller\Api;
use think\Db;
use think\Validate;
use think\Loader;

/**
 * 安防-报警记录
 */
class SafetyRecordWarning extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }


    public function readIndex()
    {

        $params = $this->request->param();

        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

        $where = [];

//        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $params['company_id']];

        $where['safety_record_warning_id'] = ['=', $params['id']];
//        dd($where);

//        if (!empty($params['job_name'])) {
//            $where['job_name'] = ['like', "%{$params['job_name']}%"];
//        }

        $count = Db::name('safety_record_warning_read')
//            ->where($where)
            ->count();

        $data = Db::name('safety_record_warning_read')
            ->where($where)
            ->limit($offset, $pageSize)
            ->order('safety_record_warning_read_id','desc')
            ->select();

        $ret = [
            'count' => $count,
            'list'  => $data,
        ];

        return api_successed($ret);

    }


    /**
     * 警报列表
     * @author lwx
     * @param post
     * @return
     */
    public function index()
    {
        $params = input();//参数


        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;

//        $company_id = $this->auth->company_id;//公司
        $company_id                = $params['company_id'];     //公司
        $company_area_id           = $params['company_area_id'] ?? 0; //区域id
        $company_device_monitor_id = $params['company_device_monitor_id'] ?? 0; //设备id

        $map = [];
        $map['company_id'] = $company_id;

        //公司区域筛选
        if (!empty($company_area_id)) {
            $map['company_area_id'] = $company_area_id;
        }

        //设备筛选
        if (!empty($company_device_monitor_id)) {
            $map['company_device_monitor_id'] = $company_device_monitor_id;
        }


        $count = Db::name('safety_record_warning')->where($map)->count();
        $rows  = Db::name('safety_record_warning')->where($map)->limit($offset, $pageSize)->select();

        foreach ($rows as $key => $row) {
            $rows[$key] = $this->tableRowFormat($row, 'atime,sleep_time,wake_time,ctime');
        }

    /*    $count = Db::name('v_safety_record_warning')->where($map)->count();
        $rows  = Db::name('v_safety_record_warning')->where($map)->limit($offset, $pageSize)->select();

        foreach ($rows as $key => $row) {
            $rows[$key] = $this->tableRowFormat($row, 'atime,sleep_time,wake_time,ctime');
        }*/
        /*
         CREATE ALGORITHM = UNDEFINED DEFINER = `root` @`%` SQL SECURITY DEFINER VIEW `t_v_safety_record_warning` AS SELECT
`w`.`safety_record_warning_id` AS `safety_record_warning_id`,
`w`.`company_id` AS `company_id`,
`w`.`company_area_id` AS `company_area_id`,
`w`.`company_device_monitor_id` AS `company_device_monitor_id`,
`w`.`user_id` AS `user_id`,
`w`.`name` AS `name`,
`w`.`desc` AS `desc`,
`w`.`content` AS `content`,
`w`.`atime` AS `atime`,
`w`.`sleep_time` AS `sleep_time`,
`w`.`wake_time` AS `wake_time`,
`w`.`sleep_num` AS `sleep_num`,
`w`.`sort` AS `sort`,
`w`.`state` AS `state`,
`w`.`ctime` AS `ctime`,
`w`.`mtime` AS `mtime`,
`dm`.`device_name` AS `device_name`,
`dm`.`type` AS `device_type`,
`dm`.`device_state` AS `device_state`,
`area`.`area_name` AS `area_name`
FROM
(
    (
        `t_safety_record_warning` `w`
        LEFT JOIN `t_company_device_monitor` `dm` ON
        (
            (
                `dm`.`company_device_monitor_id` = `w`.`company_device_monitor_id`
             )
        )
    )
		LEFT JOIN `t_company_area` `area` ON
        (
            (
			`area`.`company_area_id` = `dm`.`company_area_id`
            )
        )
)
         */
        $ret = [
            'count' => $count,
            'list'  => $rows,
        ];
        return api_successed($ret);
    }


}
