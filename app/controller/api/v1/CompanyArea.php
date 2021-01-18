<?php


namespace app\controller\api\v1;

use app\common\service\JwtService;
use app\common\validate\CompanyAreaValidate;
use app\controller\api\ApiBase;

/**
 * 公司区域
 */
class CompanyArea extends ApiBase
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
    public function index()
    {
        $params = $this->request->param();

        $companyId = JwtService::getInstance()->getCompanyId();

        $where = [];

        $where['is_deleted'] = ['=', 0];
        $where['company_id'] = ['=', $companyId];

        $data = \app\common\model\enterprise\CompanyArea::with(['director'])
            ->where($where)
            ->order('parent_id', 'asc')
            ->select();
        $data = collect($data)->toArray();
        $newData= [];
        foreach ($data as $v) {
            $temp = $v;
            $temp['director_name']  = $v['director']['username'] ?? '';

            unset($temp['director']);
            unset($temp['is_deleted']);
            array_push($newData, $temp);
        }
        $list = \app\common\model\enterprise\CompanyArea::getTreeMulti($newData);

        return json(result_successed(compact('list')));

    }


    /**
     * 公司区域添加
     * @return \think\response\Json
     */
    public function add()
    {

        $params = $this->request->only([
            'name',
            'parent_id',
            'director_id',
        ]);


        api_validate(CompanyAreaValidate::class, 'add', $params);
        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $parentId = $params['parent_id'];

        try {

            $has = \app\common\model\enterprise\CompanyArea::where([
                'parent_id'  => $parentId,
                'company_id' => $companyId,
            ])->count();

            if ($has > 0) {
                throw new \Exception('同一级区域名称不能重复');
            }

            if ($parentId  == 0) {
                $level = 1;
            } else {
                $parent = \app\common\model\enterprise\CompanyArea::find($parentId);
                $level = $parent->cur_level;
                $level++;
            }


            if ($level > 5) {
                throw new \Exception('区域最多5级');
            }

            $params['cur_level'] = $level;

            $data = \app\common\model\enterprise\CompanyArea::create($params);

        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }

        return json(result_successed());
    }

    /**
     * 修改
     * @return \think\response\Json
     */
    public function edit()
    {
        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $params = $this->request->only([
            'id',
            'parent_id',
            'name',
            'director_id',
        ]);

        api_validate(CompanyAreaValidate::class, 'edit', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        try {

            $data = \app\common\model\enterprise\CompanyArea::get($params['id']);

            $data->name = $params['name'] ?? '';

            if ($data['parent_id'] != $params['parent_id']) {
                $parent = \app\common\model\enterprise\CompanyArea::get($params['parent_id']);
//                if (in_array($params['parent_id'],$parent)){
//                    $this->error('父级不能是它的子组别或它本身');
//                }
                $level = $parent->cur_level;
                if ($level>5){
                    throw  new \Exception('级别不能超过5');
                }
                $chileLevel = \app\common\model\enterprise\CompanyArea::getChildrenMaxLevel();

            }

            $data->save();

        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }

        return json(result_successed());
    }

    /**
     * 删除
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delete()
    {

        if ($this->request->isGet()) {
            return api_failed("非法请求");
        }

        $id = input('id/d', 0);

        $data = \app\common\model\enterprise\CompanyArea::get($id);
        if (!$data) {
            return api_failed("数据不存在");
        }
        try {
            $data->is_deleted = 1;
            $data->save();
        } catch (\Exception $e) {
            return api_failed($e->getMessage());
        }

        return json(result_successed());

    }

    public function selectBox()
    {

        $companyId = JwtService::getInstance()->getCompanyId();

        $list = \app\common\model\enterprise\CompanyArea::where('is_deleted','=',0)
            ->where('company_id','=',$companyId)
            ->field(['id', 'name'])
            ->select();

        return json(result_successed(compact('list')));
    }

    /**
     * 父级区域
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function parentAreaSelect()
    {
        $companyId = JwtService::getInstance()->getCompanyId();

        $data = \app\common\model\enterprise\CompanyArea::where('is_deleted','=',0)
            ->where('company_id','=',$companyId)
//            ->field(['company_area_id', 'area_name'])
            ->select();
        $data = collect($data)->toArray();

        $list = \app\common\model\enterprise\CompanyArea::getTree($data);
        $newData = [];
        array_push($newData,[
            'parent_id' => 0,
            'name'      => '顶级区域',
            'level'     => 0,
        ]);
        foreach ($list as $v) {
            $temp = [
                'parent_id' => $v['id'],
                'name'      => $v['name'],
                'level'     => $v['level'] + 1,
            ];
            array_push($newData, $temp);
        }
        return json(result_successed(['data' => $newData]));

    }

}
