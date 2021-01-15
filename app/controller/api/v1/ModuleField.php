<?php


namespace app\controller\api\v1;

use app\controller\api\ApiBase;
use app\common\constant\IdentifyTableConstant;
use app\common\model\enterprise\UserModuleAllField;
use app\common\model\enterprise\UserModuleIdentifyField;
use app\common\service\JwtService;

/**
 * @descption 公用的下拉框
 * Class CommonSelectBox
 * @package app\api\controller\v1
 */
class ModuleField extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    private $companyId = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->companyId = input('company_id/d', 0);
        if (empty($this->companyId)) {
            return api_failed("公司id必须");
        }
    }

    public function initModule()
    {
        $id = $this->request->input('module_id/d', IdentifyTableConstant::USER);

        $moduleArr = IdentifyTableConstant::ALL_MODULE;
        $moduleName = $moduleArr[IdentifyTableConstant::USER];

        $checked = '1,2,3,4';

        try {
            $data = UserModuleIdentifyField::where([
                'company_id' => 0,
                'module_name' => $moduleName,
            ])->select();
            if (!$data) {

                UserModuleIdentifyField::create([
                    'company_id'    => 0,
                    'module_name'   => $moduleName,
                    'checked_field' => $checked,
                ]);
            } else {
                $data->checked_field = $checked;
                $data->save();
            }
        } catch (\Exception $e) {
            return json(result_failed($e->getMessage()));
        }

        return json(result_successed());
    }



    public function userIndex()
    {
        $all = $this->commonIndex(IdentifyTableConstant::USER);

        return json(result_successed(['list' => $all]));

    }

    /*
     * 用户选中字段
     */
    public function userChecked()
    {
        $params = $this->request->param();

        $this->commonChecked($params, IdentifyTableConstant::USER);

        return json(result_successed());
    }


    /*
     * 通用列表
     */
    private function commonIndex($moduleId)
    {

        $user = JwtService::getInstance()->getUserInfoMobile();

        $userid    = $user['user_id'];
        $companyId = $user['company_id'];


        $moduleArr  = IdentifyTableConstant::ALL_MODULE;
        $moduleName = $moduleArr[$moduleId];

        $all = UserModuleAllField::where('module_name',$moduleName)
            ->field([
                'id',
                'field_name as name',
                'field_value as zh_name',
            ])
            ->select();

        $all = collection($all)->toArray();

        $userChecked = UserModuleIdentifyField::where([
            'company_id'  => $companyId,
            'user_id'     => $userid,
            'module_name' => $moduleName,
        ])->value('checked_field');

        $userCheckArr = [];
        if (!empty($userChecked)) {
            $userCheckArr = explode(',', $userChecked);
        }

        if (empty($userCheckArr)) {

            $commonHas = UserModuleIdentifyField::where([
                'company_id'  => 0,
                'module_name' => $moduleName,
            ])->value('checked_field');

            if (!empty($commonHas)) {
                $userCheckArr = explode(',', $commonHas);
            }
        }

        foreach ($all as &$v) {
            $v['is_checked'] = in_array($v['id'], $userCheckArr) ? 1 : 0;
        }
        return $all;
    }

    /**
     * 公共选中的字段
     * @param $checkedIds
     * @param $moduleId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function commonChecked($params, $moduleId)
    {

        $checkedIds = $params['checked_ids'] ?? '';
        if (empty($checkedIds)) {
            return api_failed("checked_ids必须");
        }

        $user = JwtService::getInstance()->getUserInfoMobile();

        $userid    = $user['user_id'];
        $companyId = $user['company_id'];

        $moduleArr  = IdentifyTableConstant::ALL_MODULE;
        $moduleName = $moduleArr[$moduleId];

        $data = UserModuleIdentifyField::where([
            'company_id'  => $companyId,
            'user_id'     => $userid,
            'module_name' => $moduleName,
        ])->find();

        if ($data) {
            $data->checked_field = $checkedIds;
            $data->save();
        } else {
            UserModuleIdentifyField::create([
                'company_id'    => $companyId,
                'user_id'       => $userid,
                'module_name'   => $moduleName,
                'checked_field' => $checkedIds,
            ]);
        }

        return 'ok';
    }


}
