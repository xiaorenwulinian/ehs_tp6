<?php

namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\JobConstant;
use app\common\service\JwtService;
use app\common\validate\CommonValidate;
use app\common\validate\FollowPhotoValidate;
use app\common\validate\UserValidate;
use app\common\service\UserService;
use think\facade\Db;

/**
 * 会员接口
 */
class User extends ApiBase
{
//    protected $noNeedLogin = ['login','register'];
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }


    /**
     * 用户列表
     * @return \think\response\Json
     */
    public function index()
    {
        $params = $this->request->param();

        api_validate(UserValidate::class, 'index', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $params['company_id'] = $companyId;

        $ret = (new UserService())->index($params);

        return api_successed($ret);
    }


    /**
     * 用户添加
     * @return \think\response\Json
     */
    public function add()
    {

        $params = $this->request->only([
            'user_no', // 员工编号
            'username', // 账号
            'nickname', // 用户名
            'password',
            'email',
            'mobile',
            'urgency_phone', //
            'avatar',
            'sex',
            'birthday',
            'user_type',
            'department_id',
            'job_id',
            'job_role_label_id',
            'user_status', // 1.岗前学习期 2.在岗 3.离岗 4.离职'
            'config_data',
        ]);

        api_validate(UserValidate::class, 'add', $params);

        $companyId = JwtService::getInstance()->getCompanyId();
        $userId = JwtService::getInstance()->getUserId();
        $params['company_id'] = $companyId;
        $params['operator_id'] = $userId;

        $ret =  (new UserService())->add($params);
        return json($ret);
    }

    /**
     * 用户编辑
     * @return \think\response\Json
     */
    public function edit()
    {
        $params = $this->request->only([
            'id',
            'operator_id',
            'username',
            'nickname',
            'password',
            'email',
            'mobile',
            'avatar',
            'sex',
            'birthday',
            'job_ids',
        ]);

        api_validate(UserValidate::class, 'edit', $params);
        $params['company_id'] = JwtService::getInstance()->getCompanyId();
        $ret =  (new UserService())->edit($params);
        return json($ret);
    }


    /**
     * @ApiTitle  (删除员工)
    })
     */
    public function userDelete()
    {
        $params = $this->request->only([
            'id'
        ], 'POST');
        if (empty($params['id'])) {
            return api_failed('参数必须');
        }
        $params['admin_id'] = JwtService::getInstance()->getUserIdApi(); //当前账号id

        $result = (new UserService)->checkUserDelete($params);
        return json($result);
    }

    /**
     * 修改密码
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function editPassword()
    {
        $params = $this->request->param();
        $userId = $params['user_id'];
        $password = $params['password'];
        if (empty($password)) {
            return api_failed("密码必须");
        }

        $user = \app\common\model\User::get($userId);
        if (!$user) {
            return api_failed('不存在该用户');
        }

        $user->password = md5(md5($password));
        $user->save();
        return api_successed();
    }

    /**
     * 用户每日签到
     * @return \think\response\Json
     */
    public function signEveryDay()
    {
        $userId = JwtService::getInstance()->getUserIdMobile();
        $ret = (new UserService())->signEveryDay($userId);

        return json($ret);
    }


    /**
     * 随拍列表
     * @return \think\response\Json
     */
    public function followPhotoIndex()
    {
        $params = $this->request->param();

        api_validate(CommonValidate::class, 'index', $params);

        $ret = (new UserService())->followPhotoIndex($params);

        return api_successed($ret);
    }


    /**
     * 随拍添加
     * @return \think\response\Json
     */
    public function followPhotoAdd()
    {
        $params = $this->request->only([
            'company_id',
            'area_id',
            'add_time',
            'risk_theme',
            'risk_desc',
            'files',
            'audit_id',
            'device_id',
            'risk_type',
            'risk_level',
            'audit_time',
        ]);

        api_validate(FollowPhotoValidate::class, 'add', $params);

        $userId = JwtService::getInstance()->getUserIdApi();

        $params['proposer_id'] = $userId;

        $ret = (new UserService())->followPhotoAdd($params);

        return json($ret);
    }

    /**
     * 随拍审核
     * @return \think\response\Json
     */
    public function followPhotoAudit()
    {
        $params = $this->request->only([
            'id',
            'company_id',
            'risk_type',
            'risk_level',
            'audit_status'
        ]);

        api_validate(FollowPhotoValidate::class, 'edit', $params);

        $userId = JwtService::getInstance()->getUserIdApi();

        $params['operator_id'] = $userId;

        $ret = (new UserService())->followPhotoAudit($params);

        return json($ret);
    }

    /**
     * 随怕详情
     * @return \think\response\Json
     */
    public function followPhotoDetail()
    {
        $params = $this->request->param();
        api_validate(CommonValidate::class, 'detail', $params);

        $ret = (new UserService())->followPhotoDetail($params['id']);

        return json($ret);

    }

    /**
     * 审核人
     */
    public function followPhotoAuditPerson()
    {
        $id = $this->request->param('id');
        $data = \app\common\model\enterprise\CompanyArea::get($id);

        $username = Db::name('user')
            ->where('user_id', $data->director_id)
            ->value('username');

        $info = [
            'id'   =>  $data->director_id,
            'name' =>  $username,
        ];

        return api_successed(compact('info'));

    }

    public function siteAuthConfig()
    {
        $jobId = $this->request->param('id');
        $companyId = JwtService::getInstance()->getCompanyId();
        $job = Db::name('job')->where('id', $jobId)->find();

        $role = [];
        if (!empty($job['job_role_label_id'])) {
            $role= Db::name('job_role_label')
                ->field([
                    'id',
                    'name'
                ])
                ->where('id', $job['job_role_label_id'])
                ->select();
        }


        $authData = [];
        $authAll = JobConstant::AREA_AUTH_CONFIG_OBJ;
        if (!empty($job['auth_config'])) {
           $authIdArr = explode(',', $job['auth_config']);
            foreach ($authIdArr as $authId) {

                if (array_key_exists($authId, $authAll)) {
                    $v = $authAll[$authId];

                    $list = [];
                    switch ($v['identify']) {
//                        case 'company_area':
//                            $list = Db::name('company_area')
//                                ->field([
//                                    'id',
//                                    'name',
//                                ])
//                                ->where('company_id',$companyId)
//                                ->select();
//                            break;
                        case 'facility':
                            $list = Db::name('facility')
                                ->field([
                                    'id',
                                    'name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'device_camera':
                            $list = Db::name('device_camera')
                                ->field([
                                    'id',
                                    'device_no as name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'job_setting':
                            $list = Db::name('job_setting')
                                ->field([
                                    'id',
                                    'setting_no as name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'dianjian':
                            $list = Db::name('dianjian')
                                ->field([
                                    'id',
                                    'name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'device_patrol_point':
                            $list = Db::name('device_patrol_point')
                                ->field([
                                    'id',
                                    'name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'ehs_point':
                            $list = Db::name('ehs_point')
                                ->field([
                                    'id',
                                    'point_name as name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'device_limited_space':
                            $list = Db::name('device_limited_space')
                                ->field([
                                    'id',
                                    'name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                        case 'device_location_point':
                            $list = Db::name('device_location_point')
                                ->field([
                                    'id',
                                    'device_no as name',
                                ])
                                ->where('company_id',$companyId)
                                ->select();
                            break;
                    }

                    $authData[] = [
                        'id' => $v['id'],
                        'label' => $v['label'],
                        'name' => $v['name'],
                        'spanSize' => $v['span_size'],
//                        'table_name' => $v['table_name'],
                        'list' => $list,
                        'type' => $v['type'], // 2是下拉框,
                        'model' => '', // 2是下拉框,
                    ];
                }
            }

        }


        $ret = [
            'role' => $role,
            'auth_config' => $authData,

        ];

        return json(result_successed($ret));

    }



}
