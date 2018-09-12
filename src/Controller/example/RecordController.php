<?php

namespace App\WebSockets;

use ChatRoute;
use Pokeface\Chating\Controller\BaseController;
use PoChatRecord;
use PoChatChat;
use PoChatUser;

class RecordController extends BaseController
{

    public function getRecord(){
        $data = $this->getData();
        $created_at = isset($data['created_at'])?$data['created_at']:'';
        PoChatChat::singlePush(PoChatUser::getMyFd(),['message'=>PoChatRecord::getRecordByGroupId($data['group_id'],$created_at),'status'=>1]);
    }




}
