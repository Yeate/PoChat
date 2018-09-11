# PoChat
base on laravels
准备:
  编辑laravels.php配置文件
  1.修改    
    'websocket'          => [
        'enable' => true,
        'handler' => \App\Services\WebSocketService::class,
    ],
    
    'swoole_tables'      => [
        'ws' => [
            'size'   => 102400,//Table的最大行数
            'column' => [// Table的列定义
                ['name' => 'value', 'type' => 1, 'size' => 8],
            ],
        ],
    ],
    
    swoole.dispatch_mode   => 2
  2.执行
    php artisan vendor:publish --provider="Pokeface\Chating\Providers\ChatServiceProvider"
    php artisan migrate


可用方法:
  用户:
    #######
      
      PoChatUser::login($uid,$fd)
        $uid---用户ID   $fd---长连接标示ID
        
     
    
  
