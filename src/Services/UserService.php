<?php

namespace Pokeface\Chating\Services;

use Pokeface\Chating\Models\ChatGroup;
use PoChatUser;


class UserService
{

    public function getGroupId($myFd,$data,$species){
        $fromUserId = PoChatUser::getUidByFd($myFd);
        if(empty($fromUserId)){
            throw new \Exception("您未登陆",1001);
        }

        if(is_array($data['uid'])){
            $uids = array_merge([$fromUserId],$data['uid']);
        }else{
            $uids[]=$data['uid'];
            $uids[]=$fromUserId;
        }
        $ufd = $this->getUFdByUid($uids);
        if(!isset($data['group_id']) || empty($data['group_id'])){
            $data = ['uids'=>$uids,'species'=>$species];
            $group_id=ChatGroup::create($data);
            
        }else{
            $group_id = $data['group_id'];
        }
        return [$group_id,$ufd];
    }

    public function getUFdByUid($data){
        $ufd = [];
        if(!empty($data)){
            foreach($data as $v){
                $fd = $this->getFdByUid($v);
                $ufd[] = ['uid'=>$v,'fd'=>$fd];
            }
        }
        return $ufd;
    }
    // /**
    //  * [getGroupId description]
    //  * @param  [type] $from    [发信人user_id]
    //  * @param  [type] $to      [收信人user_id]
    //  * @param  [type] $species [类别 private：私聊]
    //  * @return [type]          [description]
    //  */
    // public function getGroupId($from,$to,$species){
    //     $uids = [$from];
    //     if(is_array($to)){
    //         $uids = array_merge($uids,$to);
    //     }else{
    //         $uids[]=$to;
    //     }
    //     $data = ['uids'=>$uids,'species'=>$species];
    //     $group_id=ChatGroup::create($data);
    //     return $group_id;

    // }

    public function login($uid,$fd){
        //用户 fd 双向绑定
        if(!empty($uid)){
            app('swoole')->wsTable->set('uid:' . $uid, ['value' => $fd]);// 绑定uid到fd的映射
            app('swoole')->wsTable->set('fd:' . $fd, ['value' => $uid]);// 绑定fd到uid的映射
            return true;

        }else{
            throw new \Exception("用户登陆校验失败");
        }

    }

    public function logout($fd){
        $uid = app('swoole')->wsTable->get('fd:' . $fd);
        if ($uid !== false) {
            app('swoole')->wsTable->del('uid:' . $uid['value']);// 解绑uid映射
        }
        app('swoole')->wsTable->del('fd:' . $fd);// 解绑fd映射
    }

    public function getFdByUid($uid){
        if(!empty($uid)){
            $fd = app('swoole')->wsTable->get('uid:' . $uid);
            if(!empty($fd)){
                return $fd['value'];
            }else{
                return null;
                //对象用户离线
            }
        }else{
            throw new \Exception("USERID 错误");
            
        }
    }


    public function getUidByFd($fd){
        if(!empty($fd)){
            $uid = app('swoole')->wsTable->get('fd:' . $fd);
            if(!empty($uid)){
                return $uid['value'];
            }else{
                return null;
                //对象用户离线
            }
        }else{
            throw new \Exception("fd 错误");
            
        }
    }

}