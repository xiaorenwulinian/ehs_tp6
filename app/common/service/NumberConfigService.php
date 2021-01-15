<?php

namespace app\common\service;

use app\common\constant\CommonConstant;
use app\common\constant\NumberConfigConstant;
use app\common\library\StringLib;
use app\common\model\enterprise\CompanyArea;
use app\common\model\enterprise\CompanyDeviceSetting;
use app\common\model\enterprise\Facility;
use app\common\traits\SingletonTrait;
use think\Db;
use think\helper\Str;


/**
 *
 * Class NumberConfigService
 * @package app\common\service
 */
class NumberConfigService
{

    use SingletonTrait;


    public function generatorNoJob($companyId)
    {
        $identify = 'job';

        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }

        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];



        $maxNo = Db::name('job')
            ->where('company_id', $companyId)
            ->order('id','desc')
            ->value('job_no');


        $sliceLen = strlen($alp);

        if (empty($maxNo)) {
            $no = $alp . str_pad('1',$len,'0',STR_PAD_LEFT);
        } else {


            $front = substr($maxNo, 0, $sliceLen);
            $back = substr($maxNo,  $sliceLen);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
            $back = (intval($back)) + 1;

            $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
        }

        return $no;

    }

    public function generatorNoRfid($companyId)
    {
        $identify = 'device_rfid';


        $maxNo = Db::name('device_rfid')
            ->where('company_id', $companyId)
            ->order('id','desc')
            ->value('device_no');

        $alp = '';
        $len = 5;
        if (empty($maxNo)) {
            $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
        } else {
            $slice = strlen($alp) - 1;
            $front = substr($maxNo, 0, $slice+1);
            $back = substr($maxNo,  $slice+1);
            $back = (intval($back)) + 1;

            $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
        }
        return $no;
    }


    public function generatorNoJobSetting($companyId, $areaId)
    {
        $identify = 'job_setting';
        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }



        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];

        if ($select) {

            $maxNo = Db::name('job_setting')
                ->where('company_id', $companyId)
                ->where('company_area_id', $areaId)
                ->order('id','desc')
                ->value('setting_no');

            $areaNo = Db::name('company_area')
                ->where('id', $areaId)
                ->value('area_no');

            if (empty($maxNo)) {
                $no = $alp . "-{$areaNo}-" . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {

                $slice = strrpos($maxNo,'-');

                $front = substr($maxNo, 0, $slice+1);
                $back = substr($maxNo,  $slice+1);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        } else {

            $maxNo = Db::name('job_setting')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('setting_no');
            if (empty($maxNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxNo, 0, $slice+1);
                $back = substr($maxNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }

        return $no;

    }

    private function generatorFacilityNo()
    {

        return StringLib::random(12);
    }

    public function edit($params)
    {
        $identify = $params['identify'];

        $numberAll = NumberConfigConstant::NUMBER_ARR;
        $flip = array_flip($numberAll);

        $tableName = NumberConfigConstant::TABLE_NAME_ARR[$flip[$identify]];

        $table = Db::name($tableName)
            ->where('company_id', $params['company_id'])
            ->find();
        if ($table) {
            return result_failed('已存在历史数据，不能修改');
        }

        $has = Db::table('number_config')
            ->where('company_id', $params['company_id'])
            ->where('identify', $identify)
            ->find();
        if ($has) {
            Db::table('number_config')
                ->where('company_id', $params['company_id'])
                ->where('identify', $identify)
                ->update([
                    'ext_select'    => $params['ext_select'],
                    'serial_length' => $params['serial_length'],
                    'prefix_alp'    => $params['prefix_alp'],
                ]);
        } else {
            Db::table('number_config')
                ->where('company_id', $params['company_id'])
                ->where('identify', $identify)
                ->update([
                    'ext_select'    => $params['ext_select'],
                    'serial_length' => $params['serial_length'],
                    'prefix_alp'    => $params['prefix_alp'],
                    'company_id'    => $params['company_id'],
                    'identify'      => $identify,
                ]);
        }

        return result_successed();


    }

    public function generatorNoCamera($companyId, $areaId)
    {
        $identify = 'device_camera';
        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }



        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];

        if ($select) {

            $maxSettingNo = Db::name('device_camera')
                ->where('company_id', $companyId)
                ->where('company_area_id', $areaId)
                ->order('id','desc')
                ->value('device_no');

            $areaNo = Db::name('company_area')
                ->where('id', $areaId)
                ->value('area_no');

            if (empty($maxSettingNo)) {
                $no = $alp . "-{$areaNo}-" . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {

                $slice = strrpos($maxSettingNo,'-');

                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        } else {

            $maxSettingNo = Db::name('device_camera')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('device_no');
            if (empty($maxSettingNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }

        return $no;

    }

    public function generatorNoLocationPoint($companyId, $areaId)
    {
        $identify = 'device_location_point';
        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }

        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];

        if ($select) {

            $maxSettingNo = Db::name('device_location_point')
                ->where('company_id', $companyId)
                ->where('company_area_id', $areaId)
                ->order('id','desc')
                ->value('device_no');

            $areaNo = Db::name('company_area')
                ->where('id', $areaId)
                ->value('area_no');

            if (empty($maxSettingNo)) {
                $no = $alp . "-{$areaNo}-" . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {

                $slice = strrpos($maxSettingNo,'-');

                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        } else {

            $maxSettingNo = Db::name('device_location_point')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('device_no');
            if (empty($maxSettingNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }

        return $no;

    }


    public function generatorNoVisitor($companyId)
    {
        $identify = 'visitor';

        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }

        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];

        if ($select) {

            /*$maxSettingNo = Db::name('device_camera')
                ->where('company_id', $companyId)
                ->where('company_area_id', $areaId)
                ->order('id','desc')
                ->value('device_no');

            $areaNo = Db::name('company_area')
                ->where('id', $areaId)
                ->value('area_no');

            if (empty($maxSettingNo)) {
                $no = $alp . "-{$areaNo}-" . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {

                $slice = strrpos($maxSettingNo,'-');

                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }*/
        } else {

            $maxNo = Db::name('visitor')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('visitor_no');

            if (empty($maxNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxNo, 0, $slice+1);
                $back = substr($maxNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }

        return $no;

    }

    public function generatorNoBracelet($companyId)
    {
        $identify = 'device_bracelet_machine';

        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }



        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];


        if (!$select) {
            $maxNo = Db::name('device_bracelet_machine')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('device_no');

            if (empty($maxNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxNo, 0, $slice+1);
                $back = substr($maxNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }
        return $no;

    }

    public function generatorNoIdentify($companyId)
    {
        $identify = 'device_identify_machine';

        $numberConfig = Db::name('number_config')
            ->where('company_id', $companyId)
            ->where('identify', $identify)
            ->find();

        if (!$numberConfig) {

            $numberConfig = Db::name('number_config')
                ->where('company_id', 0)
                ->where('identify', $identify)
                ->find();
        }



        $select = $numberConfig['ext_select'] === 1 ? 1 : 0;
        $len = $numberConfig['serial_length'];
        $alp = $numberConfig['prefix_alp'];


        if ($select) {

            /*$maxSettingNo = Db::name('device_camera')
                ->where('company_id', $companyId)
                ->where('company_area_id', $areaId)
                ->order('id','desc')
                ->value('device_no');

            $areaNo = Db::name('company_area')
                ->where('id', $areaId)
                ->value('area_no');

            if (empty($maxSettingNo)) {
                $no = $alp . "-{$areaNo}-" . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {

                $slice = strrpos($maxSettingNo,'-');

                $front = substr($maxSettingNo, 0, $slice+1);
                $back = substr($maxSettingNo,  $slice+1);
//                dd($numberConfig,$maxSettingNo,$areaNo,$slice,$front,$back);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }*/
        } else {

            $maxNo = Db::name('visitor')
                ->where('company_id', $companyId)
                ->order('id','desc')
                ->value('visitor_no');

            if (empty($maxNo)) {
                $no = $alp  . str_pad('1',$len,'0',STR_PAD_LEFT);
            } else {
                $slice = strlen($alp) - 1;
                $front = substr($maxNo, 0, $slice+1);
                $back = substr($maxNo,  $slice+1);
                $back = (intval($back)) + 1;

                $no = $front . str_pad($back,$len,'0',STR_PAD_LEFT);
            }
        }
        return $no;

    }

}