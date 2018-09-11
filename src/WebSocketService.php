<?php 

namespace App\Services;
use ChatRoute;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
/**
 * @see https://wiki.swoole.com/wiki/page/400.html
 */
class WebSocketService implements WebSocketHandlerInterface
{
    // 声明没有参数的构造函数
    public function __construct()
    {
        
    }
    public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        // 在触发onOpen事件之前Laravel的生命周期已经完结，所以Laravel的Request是可读的，Session是可读写的

        $server->push($request->fd, 'Welcome to LaravelS');
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
    public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
    {   
        $data = json_decode($frame->data,true);
        ChatRoute::setServer($server);
        ChatRoute::run($data['url'],$frame);
        // dd(ChatRoute::server());
        // if(isset($data['url'])){
        //     $classFun = config('socketrouter.'.$data['url']);
        //     $fun = explode('@',$classFun)['1'];
        //     $con = app()->make($data['url'])
        //     $result = app()->make($data['url'])->$fun();
        // }
        // \Log::info('Received message', [$frame->fd, $frame->data, $frame->opcode, $frame->finish]);
        // $server->push($frame->fd, date('Y-m-d H:i:s'));
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
    public function onClose(\swoole_websocket_server $server, $fd, $reactorId)
    {
        $uid = app('swoole')->wsTable->get('fd:' . $fd);
        if ($uid !== false) {
            app('swoole')->wsTable->del('uid:' . $uid['value']);// 解绑uid映射
        }
        app('swoole')->wsTable->del('fd:' . $fd);// 解绑fd映射
        $server->push($fd, 'Goodbye');
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}

 ?>