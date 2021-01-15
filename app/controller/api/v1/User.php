<?php

namespace app\controller\api\v1;

use app\common\service\JwtService;
use app\common\service\UserService;
use app\common\validate\UserValidate;
use app\controller\api\ApiBase;
use think\facade\Db;


/**
 * 会员接口
 */
class User extends ApiBase
{
//    protected $noNeedLogin = ['login','register'];
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function initialize()
    {
        parent::initialize();
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
        /*
         CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int unsigned NOT NULL DEFAULT '0' COMMENT '公司id',
  `user_no` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '员工编号',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '账号/登录名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '用户名',
  `pinyin` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拼音',
  `pinyin_short` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拼音简称',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `urgency_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '紧急联系人号码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `sex` tinyint unsigned NOT NULL DEFAULT '3' COMMENT '性别,1男，2女，3未知',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `score` int NOT NULL DEFAULT '0' COMMENT '积分',
  `createtime` int DEFAULT NULL COMMENT '创建时间',
  `updatetime` int DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：启用 ；2：禁用',
  `user_type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '用户类型：0-普通会员1-管理员',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '软删除0-正常1-删除',
  `department_id` int NOT NULL DEFAULT '0',
  `job_id` int NOT NULL DEFAULT '0',
  `job_role_label_id` int NOT NULL DEFAULT '0' COMMENT '岗位角色',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户上岗状态。1.岗前学习期 2.在岗 3.离岗 4.离职',
  `config_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '点位配置',
  PRIMARY KEY (`id`) USING BTREE,

) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员表';
         */
        $params = $this->request->only([
//            'operator_id',
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
            'company_id',
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

        return (new UserService())->edit($params);
    }


    /**
     * @ApiTitle  (删除员工)
     * @ApiMethod  (POST)
     * @ApiHeaders (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams  (name="user_id", type="string", required=true, description="用户id")
     * @ApiReturn   ({
    'code':200,
    'msg':'删除成功'
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

        $user = \app\common\model\enterprise\User::find($userId);
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
