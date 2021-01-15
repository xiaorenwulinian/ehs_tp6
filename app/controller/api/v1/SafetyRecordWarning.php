<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;

use think\facade\Db;


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

        $ret = [
            'count' => $count,
            'list'  => $rows,
        ];
        return api_successed($ret);
    }

    /**
     * 批量转换数据库格式
     * @param array row 数据表数据行
     * @param convertList 要转换的字段列表，逗号分隔
     * @param type 转换类型，默认是时间
     * @return array
     */
    private function tableRowFormat(&$row, $convertList='', $type='time'){
        if (empty($row)) {
            return;
        }

        if (empty($convertList)) {
            return;
        }

        if ($convertList) {
            $convertListArr = explode(',', $convertList);//逗号分隔转换为数组
            foreach ($convertListArr as $rowKey) {
                if (in_array($rowKey, array_keys($row))) {
                    switch ($type) {
                        case 'time':
                            $row[$rowKey] = date('Y-m-d H:i:s', $row[$rowKey]);
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            }
        }
        return $row;
    }



}
