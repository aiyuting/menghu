<?php
/**
 * Created by PhpStorm.
 * User: holden
 * Date: 2019/4/8
 * Time: 18:37
 */

namespace app\admin\controller;


use think\Controller;
use app\common\model\Authority;
use app\common\model\Access;
use app\admin\model\relationship as relationshipModel;
class Relationship extends Controller
{
    // 添加关注
    public function addAttention(){
        // 权限验证
        $userId = null;
        $flag = null;
        Authority::getInstance()->permitAll(true)->check(null)->loadAccount($flag,$userId);

        // 参数验证
        $attUserId = Access::MustParamDetect("userId");

        // 检测是否已经关注
        $data = relationshipModel::read(array("userIdList"=>array($userId),"attUserIdList"=>array($attUserId)));
        if(count($data) > 0){
            Access::Respond(0,array(),"已关注");
        }
        $ok = relationshipModel::in(array("userId"=>$userId,"attUserId"=>$attUserId));
        if(!$ok){
            Access::Respond(0,array(),"关注失败");
        }
        Access::Respond(1,array(),"关注成功");
    }

    // 取消关注
    public function delAttention(){
        // 权限验证
        $userId = null;
        $flag = null;
        Authority::getInstance()->permitAll(true)->check(null)->loadAccount($flag,$userId);

        // 参数验证
        $attUserId = Access::MustParamDetect("userId");

        // 检测是否已经关注
        $data = relationshipModel::read(array("userIdList"=>array($userId),"attUserIdList"=>array($attUserId)));
        if(count($data) <= 0){
            Access::Respond(0,array(),"未关注");
        }
        $ok = relationshipModel::del($data[0]["id"]);
        if(!$ok){
            Access::Respond(0,array(),"取消关注失败");
        }
        Access::Respond(1,array(),"取消关注成功");
    }

    // 查看关注
    public function getAttention(){
        // 权限验证
        Authority::getInstance()->permitAll(true)->check(null);
        // 参数验证
        $userId = Access::MustParamDetect("userId");

        $data = relationshipModel::read(array("userIdList"=>array($userId)));
        Access::Respond(1,$data,"获取关注情况成功");
    }
}