<?php

namespace App\WebSockets;

use ChatRoute;
use Pokeface\Chating\Controller\BaseController;
use PoChatUser;
use PoChatChat;

class UserController extends BaseController
{

    public function login(){

    	// $uid=\JWTAuth::parseToken($data['token']);
    	$uid=rand(1,1000);
    	var_dump($uid);
    	$fd = $this->getFd();
    	$login = PoChatUser::login($uid,$fd); 
    	if($login){
    		PoChatChat::singlePush($this->getFd(),['message'=>'登陆成功','status'=>1]);
    	}
    		
    	
    	
    }




    public function status(){
    	$data = $this->getData();
    	$fd = PoChatUser::getFdByUid($data['uid']);
    	if(!empty($fd)){
    		PoChatChat::singlePush($this->getFd(),['message'=>'用户在线！ FD：'.$fd,'status'=>1]);
    	}else{
    		PoChatChat::singlePush($this->getFd(),['message'=>'用户离线！','status'=>0]);
    	}
    }

}
