<?php


namespace app\api\controller\v1;

use app\api\controller\ApiBase;
use app\common\controller\Api;
use think\Validate;
use app\common\service\MemberNodeService;

/**
 * 全功能管理接口
 */
class MemberNode extends ApiBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * @descption  (全功能菜单列表)
     */
    public function nodeList()
    {

       $result = (new MemberNodeService())->getMemberNodeList();

       return json($result);
    }
}
