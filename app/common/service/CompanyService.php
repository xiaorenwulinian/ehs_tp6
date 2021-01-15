<?php

namespace app\common\service;

use think\facade\DB;

class CompanyService {


    /**
     * 获取全功能列表菜单
     * return void
     */
    public function getDetail($id){
        $field = 'company_id,name,logo';

        if (empty($id)){
            return Result(0, 'id不能为空!');
        }

        $row = Db::name('company')->field($field)->where(['company_id'=>$id])->find();

        return result_successed($row);
    }


}