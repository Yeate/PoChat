<?php 

use Pokeface\Chating\Modules\ChatRoute;


ChatRoute::pass('login','UserController@login');
ChatRoute::pass('user/status','UserController@status');

ChatRoute::pass('chat/private','ChatController@privateChat');
ChatRoute::pass('chat/group','ChatController@groupChat');

 ?>