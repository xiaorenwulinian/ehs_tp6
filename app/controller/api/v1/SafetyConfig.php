<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\validate\SafetyConfigValidate;
use think\facade\Db;

/**
 * 巡更配置
 */
class SafetyConfig extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 查询
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $id = $this->request->param('id');//修改配置

        $list = Db::name('safety_config')
            ->where(['id' => $id])
            ->find();


        return json(result_successed(compact('list')));
    }

    /**
     * 列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $company_id = input('company_id');//公司id

        $params = $this->request->param();

        $page      = !empty($params['page']) ? $params['page'] : 1;
        $pageSize  = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset    = ($page - 1) * $pageSize;


        $rows = Db::name('v_safety_config')
            ->where(['company_id'=>$company_id])
            ->limit($offset, $pageSize)
            ->select();

        $count = Db::name('v_safety_config')
            ->where(['company_id'=>$company_id])
            ->count();

        $ret = [
            'list' => $rows,
            'count' => $count,
        ];

        return api_successed($ret);
    }

     public function add()
     {
         $params = $this->request->only([
             'company_id',
             'company_device_monitor_id',
             'type',
             'static_num',
             'warn_num',
             'name',
             'desc',
         ]);

         api_validate(SafetyConfigValidate::class, 'add', $params);


         try {

             //验证是不是修改本公司的设备
             $row = Db::name('company_device_monitor')
                 ->where([
                     'company_device_monitor_id'=>$params['company_device_monitor_id'],
                     'company_id' => $params['company_id']
                 ])
                 ->find();
             if (!$row) {
                 throw new \Exception('抱歉，配置失败，本公司设备数据不存在！');
             }


             $has = Db::name('safety_config')
                 ->where(['company_device_monitor_id'=>$params['company_device_monitor_id']])
                 ->find();
             if ($has) {
                 throw new \Exception(__('Do not add configuration repeatedly'));
             }
             Db::name('safety_config')->insert($params);
         } catch (\Exception $e) {
            return api_failed($e->getMessage());
         }

         return api_successed();
     }

    public function edit()
    {
        $params = $this->request->only([
            'id',
            'company_id',
            'company_device_monitor_id',
            'type',
            'static_num',
            'warn_num',
            'name',
            'desc',
        ]);

        api_validate(SafetyConfigValidate::class, 'edit', $params);


        try {

            //验证是不是修改本公司的设备
            $row = Db::name('company_device_monitor')
                ->where([
                    'company_device_monitor_id'=>$params['company_device_monitor_id'],
                    'company_id' => $params['company_id']
                ])
                ->find();
            if (!$row) {
                throw new \Exception('抱歉，配置失败，本公司设备数据不存在！');
            }

            //修改
            Db::name('safety_config')->where(['id'=>$params['id']])->update($params);

        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }

        return api_successed();
    }


    /**
    * 删除
    * @return \think\response\Json
    * @throws \think\exception\DbException
    */
    public function delete()
    {
        $id = input('id/d',0);

        $row = Db::name('safety_config')->where(['id'=>$id])->find();
        if (!$row) {
            return api_failed("数据不存在");
        }

        try {
            Db::name('safety_config')->where(['id'=>$id])->delete();
        } catch (\Exception $e) {
            return  api_failed($e->getMessage());
        }

        return api_successed();
    }

}
