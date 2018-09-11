<?php

namespace Pokeface\Chating\Services;

use Carbon\Carbon;
use ChatRoute;
use PoChatChat;
use Pokeface\Chating\Models\ChatGroup;
use Pokeface\Chating\Models\ChatRecord;
use PoChatUser;

class ChatService
{
    /**
     * [聊天]
     * @param [type] $myFd    [本人Fd]
     * @param [type] $data    [{"url":"chat/private","data":{"uid":331,"group_id":0,"type":"text","message":"我只是测试一下"}}]
     * @param [type] $species [类型]
     */
    public function Chat($myFd,$data,$species){
        $fromUid = PoChatUser::getUidByFd($myFd);
        list($data['group_id'],$ufds) = PoChatUser::getGroupId($myFd,$data,$species);
        foreach($ufds as $_uf){
            $response = PoChatChat::formatResponse($myFd,$_uf['fd'],$data,$species);
            $this->push($_uf,$response,$fromUid);
        }
    }
    /**
     * [formatResponse description]
     * @param  [type] $fromFd  [发送人]
     * @param  [type] $toFd    [接收人]
     * @param  [type] $data    [消息数据]
     * @param  [type] $species [消息类型 private：私聊]
     * @return [type]          [description]
     */
    public function formatResponse($fromFd,$toFd,$data,$species){
        $response = ['from'=>$fromFd,'to'=>$toFd];
        switch ($data['type']){
            case 'text':
                $response['type']='text';
                $response['message']=$data['message'];
                $response['created_at']=isset($data['created_at'])?$data['created_at']:Carbon::now()->toDateTimeString();
                $response['species']=$species;
                $response['group_id']=$data['group_id'];
                break;
        }
        return $response;

    }

    public function push($uf,$response,$fromUid){
        try {
            $pushStatus=ChatRoute::server()->push($uf['fd'],json_encode(['success'=>true,'response'=>$response]));
            $this->_createChatRecord($response,$uf['uid'],$fromUid,1);
        } catch (\Exception $e) {
            $this->_createChatRecord($response,$uf['uid'],$fromUid,0);
            return false;
        }
    }

    public function singlePush($fd,$response){
        try {
            $response['type']='single';
            $pushStatus=ChatRoute::server()->push($fd,json_encode(['success'=>true,'response'=>$response]));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function _createChatRecord($response,$toUid,$fromUid,$is_send){
        $chatRecord = app(ChatRecord::class);
        $chatRecord -> from = $fromUid;
        $chatRecord -> to = $toUid;
        $chatRecord -> type = $response['type'];
        $chatRecord -> message = $response['message'];
        $chatRecord -> species = $response['species'];
        $chatRecord -> group_id = $response['group_id'];
        $chatRecord -> is_send = $is_send;
        $chatRecord -> save();
    }
}
