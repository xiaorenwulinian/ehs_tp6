<?php

namespace app\common\service;

use app\common\constant\UploadConstant;
use app\common\model\enterprise\Company;

use app\common\library\Auth;
use app\common\model\enterprise\User;
use app\common\model\enterprise\UserFollowPhoto;
use Overtrue\Pinyin\Pinyin;
use think\Db;
use think\Exception;

class UserService
{

    /**
     * 验证手机号
     * @param $mobile
     * @return bool
     */
    protected function isMobile($mobile): bool
    {
        if (preg_match('/^0?(13|14|15|17|18)[0-9]{9}$/', $mobile)) {
            return true;
        }
        return false;

    }

    /**
     * 用户基本信息验证
     * @param array $params
     * @return mixed
     */
    public function checkUser(array $params)
    {
        if (!isset($params)) {
            return Result(0, '参数有误');
        }
        $res = $this->isMobile($params['mobile']);
        if (!$res) {
            return Result(0, '请输入正确的手机号码!');
        }

        $count = Db::name('user')->where('mobile', $params['mobile'])->count();;

        if ($count != 0) {
            return Result(0, '该手机号已被注册!');
        }

        $companyName = DB::name('company')->where('title', $params['company_name'])->find();

        if (isset($companyName)) {
            return Result(0, '该公司名称已被注册!');
        }
        //测试验证码
        if ($params['smscode'] != '1234') {
            return Result(0, '验证码有误!');
        }

        $stampTime = time();
        $company = [
            'title' => $params['company_name'],
            'mobile' => $params['mobile'],
            'ctime' => $stampTime,
            'mtime' => $stampTime,
        ];

        $result = Auth::instance()->register($params['account'], $params['password'], $params['mobile'], $company);
        if ($result == false) {
            return Result(0, '注册失败，请稍后重试');
        }
        return Result(200, 'success', ['data' => $result]);


    }

    public function mobileLogin(array $params)
    {

        $company = Company::where('title', $params['company_name'])->find();

        if (!$company) {
            return api_failed('该企业不存在');
        }

        $user = User::where('username', $params['username'])
            ->where('company_id', $company['id'])
            ->find();

        if (!$user) {
            return api_failed('该用户不存在!');
        }

        if ($user->status != 1) {
            return api_failed('该账户被禁用，暂时无法登录!');
        }

        if ($user->password != md5(md5(($params['password'])))) {
            return api_failed('账号密码有误!');
        }

        $user = $user->toArray();

        $token = JwtService::getInstance()->generateTokenApi($user);

        return [
            'token' => $token,
            'data' => $user
        ];

    }

    /**
     * 用户登录验证
     * @param array $params
     * @return mixed
     */
    public function login(array $params)
    {

        $company = Company::where('title', $params['company_name'])->find();


        if (!$company) {
            return api_failed('该企业不存在');
        }

        $user = \app\common\model\enterprise\User::where('username', $params['username'])
            ->where('company_id', $company['id'])
            ->find();

        if (!$user) {
            return api_failed('该用户不存在!');
        }

        if ($user->status != 1) {
            return api_failed('该账户被禁用，暂时无法登录!');
        }

        if ($user->password != md5(md5(($params['password'])))) {
            return api_failed('账号密码有误!');
        }

        $user = $user->toArray();

        $token = JwtService::getInstance()->generateTokenApi($user);

        return [
            'token' => $token,
            'data' => $user
        ];

    }


    /*
     * 获取用户列表
     */
    public function index($params)
    {
        $page = !empty($params['page']) ? $params['page'] : 1;
        $pageSize = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset = ($page - 1) * $pageSize;

        $where = [];

        $where['is_deleted'] = ['=', 0];

        $where['company_id'] = ['=', $params['company_id']];

        if (!empty($params['username'])) {
            $where['username'] = ['like', "%{$params['username']}%"];
        }

        $count = User::where($where)->count();

        $data = User::where($where)
            ->limit($offset, $pageSize)
//            ->order('job_id','desc')
            ->select();
//        dd($data);

        $ret = [
            'count' => $count,
            'list' => $data,
        ];

        return $ret;

    }

    /**
     * 新增员工
     * @param array $params
     */
    public function add($params)
    {

        //验证是否具备新增用户权限
        $operator_id = $params['operator_id'];

        $operator = DB::name('user')->where(['id' => $operator_id])->find();

        if (!$operator && $operator['user_type'] != 1) {
            return result_failed('该账号不是管理员账号，无法添加用户操作!');
        }

        Db::startTrans();
        try {

            $has = Db::name('user')
                ->where('username', $params['username'])
                ->where('company_id', $params['company_id'])
                ->count();

            if ($has > 0) {
                throw new Exception('账号已被注册!');
            }


            $nickname = $params['nickname'] ?? '';
            $pinyin = '';
            $pinyin_short = '';
            if (!empty($nickname)) {
                $py = new Pinyin();
                $pinyin = $py->permalink($nickname,'');

                $pinyin_short = $py->abbr($nickname);
            }

            if (!empty($params['config_data'])) {
                $config_data = $params['config_data'];
                if (is_array($config_data)) {
                    $config_data = json_encode($config_data, 256);
                }
            } else {
                $config_data = json_encode([]);
            }

            $sex = !empty($params['sex']) && in_array($params['sex'],[1,2]) ? $params['sex'] : 3;
            $insert = [
                'user_no' => $params['user_no'] ?? '',
                'username' => $params['username'],
                'nickname' => $nickname,
                'pinyin' => $pinyin,
                'pinyin_short' => $pinyin_short,
                'password' => $params['password'],
                'department_id' => $params['department_id'] ?? 0,
                'job_id' => $params['job_id'] ?? 0,
                'job_role_label_id' => $params['job_role_label_id'] ?? 0,
                'user_status' => $params['user_status'] ?? 0,
                'email' => $params['email'] ?? '',
                'mobile' => $params['mobile'] ?? '',
                'user_type' => $params['user_type'] ?? 0,
                'urgency_phone' => $params['urgency_phone'] ?? '',
                'avatar' => $params['avatar'] ?? '',
                'birthday' => $params['birthday'],
                'config_data' => $config_data,
                'sex' => $sex,
            ];
            User::create($insert);

            Db::commit();

        } catch (\Exception $e) {

            Db::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    /**
     * 编辑员工
     * @param array $params
     */
    public function edit($params)
    {
        Db::startTrans();
        try {

            $data = User::find($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
            //验证是否具备新增用户权限
            $operator_id = $params['operator_id'];
            $operator = DB::name('user')->where(['user_id' => $operator_id])->find();

            if (!$operator && $operator['user_type'] != 1) {
                return api_failed('该账号不是管理员账号，无法添加用户操作!');
            }

            if ($data->username != $params['username']) {
                $has = \db('user')
                    ->where('user_id', '<>', $data->user_id)
                    ->where('username', $params['username'])
                    ->where('company_id', $params['company_id'])
                    ->count();

                if ($has > 0) {
                    throw new Exception('账号已被注册!');
                }
            }

            // 部门 岗位
            if (empty($params['job_ids'])) {
                throw new Exception('岗位信息不存在!');
            }

            $jobIds = $params['job_ids'];

            $jobIdArr = explode(',', $jobIds);
            $jobIdArr = array_unique($jobIdArr);

            unset($params['operator_id']);
            unset($params['position_data']);

            $data->save($params);

            Db::commit();


        } catch (\Exception $e) {

            Db::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    /**
     * 删除用户操作
     * @param array $params
     */
    public function checkUserDelete(array $params)
    {

        $userData = DB::name('user')
            ->field('user_type,company_id')
            ->where(['id' => $params['admin_id'], 'is_deleted' => 0])
            ->find();
        if (isset($userData) && $userData['user_type'] != 1) {
            return Result(0, '该账号不是管理员账号，无法进行删除用户操作!');
        }

        $user = DB::name('user')
            ->field('user_type,company_id')
            ->where(['id' => $params['user_id'], 'is_deleted' => 0])
            ->find();
        if (empty($user)) return Result(0, '该用户不存在，操作失败!');
        if ($userData['company_id'] != $user['company_id']) {
            return Result(0, '非同一公司，无法进行操作!');
        }
        DB::name('user')->where('id', $params['id'])->update([
            'is_deleted' => 1
        ]);
        return Result(200, '删除成功');


    }


    public function signEveryDay($userId)
    {
        try {
            $user = User::get($userId);
            if (!$user) {
                throw new \Exception('未发现该数据');
            }

            $curDay = date('Y-m-d');
            $has = Db::name('user_sign_log')
                ->where('user_id', $userId)
                ->where('sign_day', $curDay)
                ->count();

            if ($has > 0) {
                throw new \Exception("今日已签到");
            }

            $time = date('Y-m-d H:i:s');
            Db::name('user_sign_log')->insert([
                'user_id' => $userId,
                'company_id' => $user->company_id,
                'sign_day' => $curDay,
                'sign_time' => $time,
            ]);

            (new IntegralLogService())->userSign($userId);

        } catch (\Exception $e) {
            return result_failed($e->getMessage());
        }

        return result_successed();
    }

    /*
    * 获取随拍列表
    */
    public function followPhotoIndex($params)
    {

        $page     = !empty($params['page']) ? $params['page'] : 1;
        $pageSize = !empty($params['page_size']) ? $params['page_size'] : 10;
        $offset   = ($page - 1) * $pageSize;

        $where = [];

       // $where['is_deleted'] = ['=', 0];

        $where['company_id'] = ['=', $params['company_id']];

//        if (!empty($params['username'])) {
//            $where['username'] = ['like', "%{$params['username']}%"];
//        }

        $count = UserFollowPhoto::with([
            'companyArea',
            'proposerUser',
            'companyDevice',
        ])
            ->where($where)
            ->count();

        $data = UserFollowPhoto::with([
            'companyArea',
            'proposerUser',
            'auditUser',
            'companyDevice',
        ])
            ->where($where)
            ->limit($offset, $pageSize)
//            ->order('job_id','desc')
            ->select();

        $auditStatusArr = UserFollowPhoto::AUDIT_STATUS_ARR;
        $data = collection($data)->toArray();
        foreach ($data as &$v) {
            $v['audit_status_string'] = $auditStatusArr[$v['audit_status']] ?? '';

            $v['area_name'] = $v['company_area']['area_name'] ?? '';
            $v['proposer_name'] = $v['proposer_user']['username'] ?? '';
            $v['audit_name'] = $v['audit_user']['username'] ?? '';
            $v['device_name'] = $v['company_device_setting']['device_name'] ?? '';
            unset($v['company_area']);
            unset($v['audit_user']);
            unset($v['proposer_user']);
            unset($v['company_device_setting']);
        }
//        dd($data);

        $ret = [
            'count' => $count,
            'list' => $data,
        ];

        return $ret;

    }


    public function followPhotoDetail($id)
    {
        $data = UserFollowPhoto::get($id);
        if (!$data) {
            return result_failed("数据不存在");
        }

        $files = [];
        if ($data->files) {
            $files = explode(',', $data->files);
        }

        $imageArr  = [];
        $videoArr = [];

        $imgExtensionArr   = UploadConstant::UPLOAD_ALLOW_IMG_EXT;
        $videoExtensionArr = UploadConstant::UPLOAD_ALLOW_VIDEO_EXT;

        $domain = request()->domain();

        foreach ($files as $file) {
            list($filename, $ext) = explode('.', $file);
            $ext = strtolower($ext);
            if (in_array($ext, $imgExtensionArr)) {
                $imageArr[] = $domain . '/' . $file;
            }

            if (in_array($ext, $videoExtensionArr)) {
                $videoArr[] = $domain . '/' . $file;
            }
        }

        $fileArr = [
            'image' => $imageArr,
            'video' => $videoArr,
        ];

        $data->domain_files_arr = $fileArr;

        $ret = [
            'info' => $data
        ];

        return result_successed($ret);
    }


    /*
     * 随拍新增
     */

    public function followPhotoAdd($params)
    {

        try {

            $params['add_time'] = date('Y-m-d H:i:s');
            $params['audit_status'] = UserFollowPhoto::AUDIT_STATUS_AUDIT_WAIT;
//            $params['integral_num'] = 6;

            Db::name('user_follow_photo')->insert($params);

        } catch (\Exception $e) {

            return result_failed($e->getMessage());

        }

        return result_successed();

    }

    /*
     * 随拍审核
     */
    public function followPhotoAudit($params)
    {
        Db::startTrans();
        try {

            $data = UserFollowPhoto::get($params['id']);
            if (!$data) {
                throw new \Exception("未发现该数据");
            }

            if ($data->company_id != $params['company_id']) {
                throw new \Exception("只能操作本公司数据");
            }
            //验证是否为审核人
            if ($data->audit_id != $params['operator_id']) {
                throw new \Exception("只能审核人操作数据");
            }

            if ($data->audit_status != 11) {
                throw new \Exception("不能重复操作数据");
            }

            $curTime = date('Y-m-d H:i:s');
            if ($params['audit_status'] == 21) {
                // 审核通过送积分
               /* if ($data->integral_num > 0) {

                    $logRet = (new IntegralLogService())->followPhoto($data->proposer_id, $data->integral_num);

                    if (!$logRet) {
                        throw new \Exception("积分赠送失败！");
                    }
                }*/
                $logRet = (new IntegralLogService())->followPhoto($data->proposer_id);
            }

            $data->risk_type    = $params['risk_type'];
            $data->risk_level   = $params['risk_level'];
            $data->audit_status = $params['audit_status'];
            $data->audit_time   = $curTime;

            $data->save();

            Db::commit();

        } catch (\Exception $e) {

            Db::rollback();
            return result_failed($e->getMessage());
        }

        return result_successed();

    }


    public function getIntegralInfo($score, $levelAll)
    {
        $beforeScore = 0;
        $info = [];
        foreach ($levelAll as $v) {
            if ($score === 0) {
                $info = $v;
                $info['cur_level'] = 1;
                break;
            }
            $upgrade_score = $v['upgrade_score'];
            $min_level = $v['min_level'];
            $max_level = $v['max_level'];
            $tempSum  = $beforeScore;
            $tempMinScore = $tempSum;

            $tempMaxScore = $tempSum + $upgrade_score * ($max_level - $min_level + 1);

            if ($score >= $tempMinScore && $score < $tempMaxScore) {
                $mod = ($score - $tempMinScore) / $upgrade_score;
                $cur_level = intval(floor($mod)) + $min_level;
                $info = $v;
                $info['min_s'] = $tempMinScore;
                $info['max_s'] = $tempMaxScore;
                $info['cur_level'] = $cur_level;
                break;
            }

            $beforeScore = $tempMaxScore;
        }

        return $info;
    }

}