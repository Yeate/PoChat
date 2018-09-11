<?php

namespace Pokeface\Chating\Modules;



class ChatRoute
{
    protected $map;

    protected $socket_server;

    protected $namespace='App\\WebSockets\\';


    public function __construct(){
        $this->map = array();
        $this->loadSocketRoutes(base_path('routes/websocket.php'));
    }

    public function pass($path,$action){
        $actionArr = explode('@', $action);
        $this->map[$path]=['class'=>$actionArr['0'],'method'=>$actionArr['1']];
        app()->bind($path, function () use($actionArr) {
            $class = $this->namespace.$actionArr['0'];
            return new $class();
        });
    }

    public function run($path,$frame){
        try{
            $result = app()->make($path);
            $result->setFd($frame->fd)->setOpcode($frame->opcode)->setFinish($frame->finish);
            $data = json_decode($frame->data,true);
            if(isset($data['data'])){
                $result->setData($data['data']);
            }
            $result->{$this->map[$path]['method']}($path);
        }catch(\Exception $e){
            \Log::error($e->getMessage(), ['filepath' => $e->getFile().$e->getLine()]);
            $errorStr = json_encode(['success'=>false,'message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->socket_server->push($frame->fd, $errorStr);
        }

    }

    public function server(){
        return $this->socket_server;
    }

    public function setServer($server){
        $this->socket_server = $server;
        return $this;


    }


    protected function loadSocketRoutes($path)
    {
        if (empty($this->map)) {
            require $path;
        }
    }
    
}
