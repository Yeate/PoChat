<?php 
namespace Pokeface\Chating\Facades;


use Illuminate\Support\Facades\Facade;

class UserServiceFacade extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PoChatUser';
    }

}