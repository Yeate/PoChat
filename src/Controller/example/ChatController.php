<?php

namespace App\WebSockets;

use ChatRoute;
use Pokeface\Chating\Controller\BaseController;
use PoChatUser;
use PoChatChat;

class ChatController extends BaseController
{

    public function privateChat(){
        $data = $this->getData();
        PoChatChat::Chat($data,'private');
    }

    public function groupChat(){
        

    }


}
