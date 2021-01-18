<?php


namespace app\controller\api\v1;

use app\common\model\DepartmentModel;
use app\common\model\JobModel;
use app\common\model\UserModel;
use app\controller\api\ApiBase;
use app\common\service\DepartmentService;
use app\common\service\JwtService;
use app\common\validate\DepartmentValidate;

/**
 * 部门
 */
class Department extends ApiBase
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

//        api_validate(DepartmentValidate::class, 'index', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DepartmentService())->index($params);

        return json($ret);

    }


    /**
     * @descption 部门添加
     * @author lcl
     * @date 2020/11/23
     * @return \think\response\Json
     */
    public function add()
    {

        $params = $this->request->only([
            'parent_id',
            'department_name',
            'duty_user_id',

        ]);

        api_validate(DepartmentValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new DepartmentService())->add($params);
        return json($ret);
     }

    /**
     * 修改
     * @author lcl
     * @date 2020/11/23
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
             'department_name',
             'duty_user_id',
         ]);

         api_validate(DepartmentValidate::class, 'edit', $params);

         $companyId = JwtService::getInstance()->getCompanyId();
         $params['company_id'] = $companyId;

         $ret = (new DepartmentService())->edit($params);
         return json($ret);


     }


    /**
     * 删除
     * @author lcl
     * @date 2020/11/23
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delete()
    {
        $id = input('id/d',0);

        $ret = (new DepartmentService())->delete($id);
        return json($ret);

    }

    /**
     *
     * 父级下拉框
     */
    public function parentSelectBox()
    {
        $companyId = input('company_id/d',0);
        $data = DepartmentModel::where('company_id',$companyId)
            ->where('is_deleted',0)
            ->field(['id','parent_id','department_name'])
            ->order('parent_id','asc')
            ->select();

        $data = collect($data)->toArray();
        $list = DepartmentModel::getTree($data);

        $newData = [];
        foreach ($list as $v) {

            $space = str_repeat('--', $v['level']);
            if (!empty($space)) {
                $space .= ' ';
            }

            $temp = [
                'id'    => $v['id'],
//                'name'  => $v['department_name'],
                'name'  => $space . $v['department_name'],
                'level' => $v['level'],
            ];
            array_push($newData, $temp);
        }

        return json(result_successed(['list' => $newData]));

    }

    /**
     * 岗位
     */
    public function job()
    {
        $departmentId = input('id');


        $list = JobModel::where('is_deleted','=',0)
            ->where('department_id','=', $departmentId)
            ->field(['id', 'job_name as name'])
            ->select();

        return json(result_successed(['list' => $list]));

    }

    public function info()
    {
        $departmentId = input('id');
        $job = JobModel::where('is_deleted','=',0)
            ->where('department_id','=', $departmentId)
            ->field(['id', 'job_name as name'])
            ->select();

        $user = UserModel::where('is_deleted','=',0)
            ->where('department_id','=', $departmentId)
            ->field(['id', 'username as name'])
            ->select();
        $user = collect($user)->toArray();
        foreach ($user as &$v) {
            unset($v['domain_avatar']);
            unset($v['sex_string']);
        }

        $ret = [
            'job' => $job,
            'user' => $user,
        ];

        return json(result_successed($ret));
    }

}
