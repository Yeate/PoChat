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
    protected $response;
    /**
     * [聊天]
     * @param [type] $myFd    [本人Fd]
     * @param [type] $data    [{"url":"chat/private","data":{"uid":331,"group_id":0,"type":"text","message":"我只是测试一下"}}]
     * @param [type] $species [类型]
     */
    public function Chat($data,$species){
        \Log::info('fd:'.PoChatUser::getMyFd());
        list($data['group_id'],$ufds) = PoChatUser::getGroupId($data,$species);
        foreach($ufds as $_uf){
            $response = $this->formatResponse($_uf['uid'],$data,$species)->push($_uf);
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


    /**
     * [formatResponse description]
     * @param  [type] $toUid    [接收人]
     * @param  [type] $data    [消息数据]
     * @param  [type] $species [消息类型 private：私聊]
     * @return [type]          [description]
     */
    private function formatResponse($toUid,$data,$species){
        $response = ['from'=>PoChatUser::getMyUid(),'to'=>$toUid];
        switch ($data['type']){
            case 'text':
                $response['type']='text';
                $response['message']=$data['message'];
                $response['created_at']=isset($data['created_at'])?$data['created_at']:Carbon::now()->toDateTimeString();
                $response['species']=$species;
                $response['group_id']=$data['group_id'];
                break;
        }
        $this->response=$response;
        return $this;

    }

    private function push($uf){
        try {
            $pushStatus=ChatRoute::server()->push($uf['fd'],json_encode(['success'=>true,'response'=>$this->response]));
            $this->_createChatRecord($uf['uid'],1);
        } catch (\Exception $e) {
            $this->_createChatRecord($uf['uid'],0);
            return false;
        }
    }



    private function _createChatRecord($toUid,$is_send){
        $chatRecord = app(ChatRecord::class);
        $chatRecord -> from = PoChatUser::getMyUid();
        $chatRecord -> to = $toUid;
        $chatRecord -> type = $this->response['type'];
        $chatRecord -> message = $this->response['message'];
        $chatRecord -> species = $this->response['species'];
        $chatRecord -> group_id = $this->response['group_id'];
        $chatRecord -> is_send = $is_send;
        $chatRecord -> save();
    }
}
