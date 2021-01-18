<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::group('/api/v1', function(){
    Route::get('/', function () {
        return 'hello,api!';
    });

    Route::group('check_rate', function () {
        Route::any('index', 'api.v1.CheckRate/index');
        Route::any('add', 'api.v1.CheckRate/add');
        Route::any('edit', 'api.v1.CheckRate/edit');
        Route::any('delete', 'api.v1.CheckRate/delete');

    });

    Route::group('Common_dict', function () {
        Route::any('index', 'api.v1.CommonDict/index');
        Route::any('edit', 'api.v1.CommonDict/edit');

    });

    Route::group('common_select_box', function () {
        Route::any('companyArea', 'api.v1.CommonSelectBox/companyArea');
        Route::any('companyAreaMulti', 'api.v1.CommonSelectBox/companyAreaMulti');
        Route::any('job', 'api.v1.CommonSelectBox/job');
        Route::any('checkRate', 'api.v1.CommonSelectBox/checkRate');
        Route::any('user', 'api.v1.CommonSelectBox/user');
        Route::any('companyDeviceMonitor', 'api.v1.CommonSelectBox/companyDeviceMonitor');
        Route::any('ehsPointCheckTime', 'api.v1.CommonSelectBox/ehsPointCheckTime');
        Route::any('ehsCheckStand', 'api.v1.CommonSelectBox/ehsCheckStand');
        Route::any('department', 'api.v1.CommonSelectBox/department');
        Route::any('deviceState', 'api.v1.CommonSelectBox/deviceState');
        Route::any('deviceType', 'api.v1.CommonSelectBox/deviceType');
        Route::any('riskLevel', 'api.v1.CommonSelectBox/riskLevel');
        Route::any('rfidDevice', 'api.v1.CommonSelectBox/rfidDevice');
        Route::any('accidentType', 'api.v1.CommonSelectBox/accidentType');
        Route::any('nonconformingType', 'api.v1.CommonSelectBox/nonconformingType');
        Route::any('trainType', 'api.v1.CommonSelectBox/trainType');
        Route::any('studyContent', 'api.v1.CommonSelectBox/studyContent');
        Route::any('accidentLevel', 'api.v1.CommonSelectBox/accidentLevel');
        Route::any('hazardousType', 'api.v1.CommonSelectBox/hazardousType');
        Route::any('camera', 'api.v1.CommonSelectBox/camera');
        Route::any('ppeType', 'api.v1.CommonSelectBox/ppeType');
        Route::any('occupationalDiseaseKind', 'api.v1.CommonSelectBox/occupationalDiseaseKind');
        Route::any('occupationalDisease', 'api.v1.CommonSelectBox/occupationalDisease');
        Route::any('specialWork', 'api.v1.CommonSelectBox/specialWork');
        Route::any('areaPermissionConfig', 'api.v1.CommonSelectBox/areaPermissionConfig');
        Route::any('riskColor', 'api.v1.CommonSelectBox/riskColor');
        Route::any('safetySignsColor', 'api.v1.CommonSelectBox/safetySignsColor');
        Route::any('workType', 'api.v1.CommonSelectBox/workType');
        Route::any('workType', 'api.v1.CommonSelectBox/workType');
    });

    Route::group('company', function () {
        Route::any('detail', 'api.v1.Company/detail');
    });

    Route::group('company_area', function () {
        Route::any('index', 'api.v1.CompanyArea/index');
        Route::any('add', 'api.v1.CompanyArea/add');
        Route::any('edit', 'api.v1.CompanyArea/edit');
        Route::any('delete', 'api.v1.CompanyArea/delete');
        Route::any('selectBox', 'api.v1.CompanyArea/selectBox');
        Route::any('parentAreaSelect', 'api.v1.CompanyArea/parentAreaSelect');
    });

    Route::group('company_device_monitor', function () {
        Route::any('index', 'api.v1.CompanyDeviceMonitor/index');
        Route::any('add', 'api.v1.CompanyDeviceMonitor/add');
        Route::any('edit', 'api.v1.CompanyDeviceMonitor/edit');
        Route::any('delete', 'api.v1.CompanyDeviceMonitor/delete');
    });

    Route::group('department', function () {
        Route::any('index', 'api.v1.Department/index');
        Route::any('add', 'api.v1.Department/add');
        Route::any('edit', 'api.v1.Department/edit');
        Route::any('delete', 'api.v1.Department/delete');
        Route::any('parentSelectBox', 'api.v1.Department/parentSelectBox');
        Route::any('job', 'api.v1.Department/job');
        Route::any('info', 'api.v1.Department/info');
    });

    Route::group('device', function () {
        Route::any('rfidIndex', 'api.v1.Device/rfidIndex');
        Route::any('rfidAdd', 'api.v1.Device/rfidAdd');
        Route::any('rfidEdit', 'api.v1.Device/rfidEdit');
        Route::any('locationPointIndex', 'api.v1.Device/locationPointIndex');
        Route::any('locationPointAdd', 'api.v1.Device/locationPointAdd');
        Route::any('patrolPointIndex', 'api.v1.Device/patrolPointIndex');
        Route::any('patrolPointAdd', 'api.v1.Device/patrolPointAdd');
        Route::any('patrolPointEdit', 'api.v1.Device/patrolPointEdit');
        Route::any('checkPointIndex', 'api.v1.Device/checkPointIndex');
        Route::any('braceletMachineIndex', 'api.v1.Device/braceletMachineIndex');
        Route::any('braceletMachineAdd', 'api.v1.Device/braceletMachineAdd');
        Route::any('braceletMachineEdit', 'api.v1.Device/braceletMachineEdit');
        Route::any('identifyMachineIndex', 'api.v1.Device/identifyMachineIndex');
        Route::any('identifyMachineAdd', 'api.v1.Device/identifyMachineAdd');
        Route::any('identifyMachineEdit', 'api.v1.Device/identifyMachineEdit');
        Route::any('cameraIndex', 'api.v1.Device/cameraIndex');
        Route::any('cameraAdd', 'api.v1.Device/cameraAdd');
        Route::any('cameraEdit', 'api.v1.Device/cameraEdit');
        Route::any('limitedSpaceIndex', 'api.v1.Device/limitedSpaceIndex');
        Route::any('limitedSpaceAdd', 'api.v1.Device/limitedSpaceAdd');
        Route::any('limitedSpaceEdit', 'api.v1.Device/limitedSpaceEdit');
    });

    Route::group('ehs_point', function () {
        Route::any('index', 'api.v1.EhsPoint/index');
        Route::any('add', 'api.v1.EhsPoint/add');
        Route::any('edit', 'api.v1.EhsPoint/edit');
        Route::any('delete', 'api.v1.EhsPoint/delete');
        Route::any('courseIndex', 'api.v1.EhsPoint/courseIndex');
        Route::any('courseAdd', 'api.v1.EhsPoint/courseAdd');
    });

    Route::group('ehs_point_check_time', function () {
        Route::any('index', 'api.v1.EhsPointCheckTime/index');
        Route::any('add', 'api.v1.EhsPointCheckTime/add');
        Route::any('edit', 'api.v1.EhsPointCheckTime/edit');
    });

    Route::group('facility', function () {
        Route::any('index', 'api.v1.Facility/index');
        Route::any('add', 'api.v1.Facility/add');
        Route::any('edit', 'api.v1.Facility/edit');
        Route::any('detail', 'api.v1.Facility/detail');
    });

    Route::group('file', function () {
        Route::any('upload', 'api.v1.File/upload');
        Route::any('uploadVideo', 'api.v1.File/uploadVideo');
        Route::any('test', 'api.v1.File/test');
    });

    Route::group('hardware', function () {
        Route::any('faintWarning', 'api.v1.Hardware/faintWarning');
        Route::any('safetyConfig', 'api.v1.Hardware/safetyConfig');
        Route::any('safetyRecordWarningAdd', 'api.v1.Hardware/safetyRecordWarningAdd');
        Route::any('get_device_data_by_machine_no', 'api.v1.Hardware/get_device_data_by_machine_no');
        Route::any('get_device_by_id', 'api.v1.Hardware/get_device_by_id');

    });

    Route::group('job', function () {
        Route::any('index', 'api.v1.Job/index');
        Route::any('addShow', 'api.v1.Job/addShow');
        Route::any('add', 'api.v1.Job/add');
        Route::any('edit', 'api.v1.Job/edit');
        Route::any('delete', 'api.v1.Job/delete');
        Route::any('qualifyIndex', 'api.v1.Job/qualifyIndex');
        Route::any('specialWorkIndex', 'api.v1.Job/specialWorkIndex');
        Route::any('ppeIndex', 'api.v1.Job/ppeIndex');
        Route::any('ppeAdd', 'api.v1.Job/ppeAdd');
        Route::any('ppeEdit', 'api.v1.Job/ppeEdit');
        Route::any('emergencyPlanIndex', 'api.v1.Job/emergencyPlanIndex');
        Route::any('emergencyPlanAdd', 'api.v1.Job/emergencyPlanAdd');
        Route::any('emergencyPlanEdit', 'api.v1.Job/emergencyPlanEdit');
        Route::any('dangerSourceIndex', 'api.v1.Job/dangerSourceIndex');
        Route::any('environmentFactorIndex', 'api.v1.Job/environmentFactorIndex');
        Route::any('roleLabelIndex', 'api.v1.Job/roleLabelIndex');
        Route::any('detail', 'api.v1.Job/detail');
//        Route::any('bindPpe', 'api.v1.Job/bindPpe');
        Route::any('bindPpe/add', 'api.v1.Job/bindPpeAdd');
        Route::any('bindPpe/edit', 'api.v1.Job/bindPpeEdit');
        Route::any('bindPpe/delete', 'api.v1.Job/bindPpeDelete');
        Route::any('bindPpeDetail', 'api.v1.Job/bindPpeDetail');

        Route::any('bindCourse/add', 'api.v1.Job/bindCourseAdd');
        Route::any('bindCourse/delete', 'api.v1.Job/bindCourseDelete');
//        Route::any('course/unbind', 'api.v1.Job/courseUnbind');
        Route::any('bindCourseDetail', 'api.v1.Job/bindCourseDetail');

        Route::any('bindEmergency/add', 'api.v1.Job/bindEmergencyAdd');
        Route::any('bindEmergency/delete', 'api.v1.Job/bindEmergencyDelete');
//        Route::any('bindEmergency', 'api.v1.Job/bindEmergency');
        Route::any('bindEmergencyDetail', 'api.v1.Job/bindEmergencyDetail');
    });

    Route::group('job_avoid', function () {
        Route::any('index', 'api.v1.JobAvoid/index');
        Route::any('add', 'api.v1.JobAvoid/add');
        Route::any('edit', 'api.v1.JobAvoid/edit');
        Route::any('delete', 'api.v1.JobAvoid/delete');
    });

    Route::group('job_setting', function () {
        Route::any('JobSettingIndex', 'api.v1.JobSetting/JobSettingIndex');
        Route::any('JobSettingAdd', 'api.v1.JobSetting/JobSettingAdd');
    });

    Route::group('login', function () {
        Route::any('register', 'api.v1.Login/register');
        Route::any('login', 'api.v1.Login/login');
    });

    Route::group('member_node', function () {
        Route::any('nodeList', 'api.v1.MemberNode/nodeList');
    });

    Route::group('module_field', function () {
        Route::any('initModule', 'api.v1.ModuleField/initModule');
        Route::any('userIndex', 'api.v1.ModuleField/userIndex');
        Route::any('userChecked', 'api.v1.ModuleField/userChecked');
        Route::any('commonIndex', 'api.v1.ModuleField/commonIndex');
        Route::any('commonChecked', 'api.v1.ModuleField/commonChecked');
    });

    Route::group('number_config', function () {
        Route::any('detail', 'api.v1.NumberConfig/detail');
        Route::any('edit', 'api.v1.NumberConfig/edit');
    });

    Route::group('occupational', function () {
        Route::any('harmFactorIndex', 'api.v1.Occupational/harmFactorIndex');
        Route::any('add', 'api.v1.Occupational/add');
        Route::any('ocTabooIndex', 'api.v1.Occupational/ocTabooIndex');
        Route::any('ocDetail', 'api.v1.Occupational/ocDetail');
        Route::any('testPlanIndex', 'api.v1.Occupational/testPlanIndex');
        Route::any('testPlanAdd', 'api.v1.Occupational/testPlanAdd');
        Route::any('testPlanEdit', 'api.v1.Occupational/testPlanEdit');
    });

    Route::group('record_check_point', function () {
        Route::any('index', 'api.v1.RecordCheckPoint/index');
        Route::any('add', 'api.v1.RecordCheckPoint/add');
    });

    Route::group('safety_config', function () {
        Route::any('detail', 'api.v1.SafetyConfig/detail');
        Route::any('index', 'api.v1.SafetyConfig/index');
        Route::any('add', 'api.v1.SafetyConfig/add');
        Route::any('edit', 'api.v1.SafetyConfig/edit');
        Route::any('delete', 'api.v1.SafetyConfig/delete');
    });

    Route::group('safety_record_warning', function () {
        Route::any('readIndex', 'api.v1.SafetyRecordWarning/readIndex');
        Route::any('index', 'api.v1.SafetyRecordWarning/index');
    });

    Route::group('user', function () {
        Route::any('index', 'api.v1.user/index');
        Route::any('add', 'api.v1.user/add');
        Route::any('edit', 'api.v1.user/edit');
        Route::any('userDelete', 'api.v1.user/userDelete');
        Route::any('editPassword', 'api.v1.user/editPassword');
        Route::any('signEveryDay', 'api.v1.user/signEveryDay');
        Route::any('followPhotoIndex', 'api.v1.user/followPhotoIndex');
        Route::any('followPhotoAdd', 'api.v1.user/followPhotoAdd');
        Route::any('followPhotoAudit', 'api.v1.user/followPhotoAudit');
        Route::any('followPhotoDetail', 'api.v1.user/followPhotoDetail');
        Route::any('followPhotoAuditPerson', 'api.v1.user/followPhotoAuditPerson');
        Route::any('siteAuthConfig', 'api.v1.user/siteAuthConfig');
    });

    Route::group('visitor', function () {
        Route::any('index', 'api.v1.Visitor/index');
        Route::any('add', 'api.v1.Visitor/add');
    });

    Route::group('work', function () {
        Route::any('highIndex', 'api.v1.Work/highIndex');
        Route::any('highAdd', 'api.v1.Work/highAdd');
        Route::any('highEdit', 'api.v1.Work/highEdit');
        Route::any('fireIndex', 'api.v1.Work/fireIndex');
        Route::any('fireAdd', 'api.v1.Work/fireAdd');
        Route::any('dirtIndex', 'api.v1.Work/dirtIndex');
        Route::any('dirtAdd', 'api.v1.Work/dirtAdd');
        Route::any('electricIndex', 'api.v1.Work/electricIndex');
        Route::any('electricAdd', 'api.v1.Work/electricAdd');
        Route::any('limitSpareIndex', 'api.v1.Work/limitSpareIndex');
        Route::any('limitSpareAdd', 'api.v1.Work/limitSpareAdd');
        Route::any('slingIndex', 'api.v1.Work/slingIndex');
        Route::any('slingAdd', 'api.v1.Work/slingAdd');
        Route::any('cuttingOutIndex', 'api.v1.Work/cuttingOutIndex');
        Route::any('cuttingOutAdd', 'api.v1.Work/cuttingOutAdd');
        Route::any('blindIndex', 'api.v1.Work/blindIndex');
        Route::any('blindAdd', 'api.v1.Work/blindAdd');
        Route::any('workCommonShow', 'api.v1.Work/workCommonShow');
        Route::any('userSelect', 'api.v1.Work/userSelect');
    });

    Route::group('strategy', function () {
        Route::any('policy/show', 'api.v1.Strategy/policyShow');
        Route::any('policy/save', 'api.v1.Strategy/policySave');
        Route::any('goal/index', 'api.v1.Strategy/goalIndex');
        Route::any('goal/add', 'api.v1.Strategy/goalAdd');
        Route::any('goal/edit', 'api.v1.Strategy/goalEdit');
        Route::any('goal/delete', 'api.v1.Strategy/goalDelete');
    });


    // 需要 token 身份验证验证
   /* Route::group('', function () {
        // 用户
        Route::group('user', function () {
            Route::get('detail', 'miniprogram.user/detail'); // 获取用户信息
        });


    })->middleware([app\middleware\MiniProgramAuthCheck::class]);
    */
   /* Route::group('login', function () {
        Route::get('login', 'miniprogram.login/login');
        Route::get('test', function () {

            return config('miniprogram.app_id');
            return cache('obQ1t5RZjW2z-o71pVYcM9Fruf4Y');
//            'LM/517MWSs4mTftudHszyg=='

            return 'hello,admin!';
        });
        Route::any('getOpenidByCode', 'miniprogram.login/getOpenidByCode');

    });*/



})->middleware([app\middleware\ApiCorsMiddleware::class]);

