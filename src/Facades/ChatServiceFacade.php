<?php 
namespace Pokeface\Chating\Facades;


use Illuminate\Support\Facades\Facade;

class ChatServiceFacade extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PoChatChat';
    }

}