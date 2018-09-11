<?php

namespace Pokeface\Chating\Controller;


class BaseController 
{
    private $fd;
    private $opcode;
    private $finish;
    private $data;

    public function setFd($fd){
        $this->fd = $fd;
        return $this;
    }

    public function getFd(){
        return $this->fd;
    }

    public function setOpcode($opcode){
        $this->opcode = $opcode;
        return $this;
    }

    public function getOpcode(){
        return $this->opcode;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function getData(){
        return $this->data;
    }

    public function setFinish($finish){
        $this->finish = $finish;
        return $this;
    }

    public function getFinish(){
        return $this->finish;
    }
}

