<?php 
namespace Pokeface\Chating\Facades;


use Illuminate\Support\Facades\Facade;

class ChatRouteFacade extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChatRoute';
    }

}