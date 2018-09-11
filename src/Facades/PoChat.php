<?php 
namespace Pokeface\Chating\Facades;


use Illuminate\Support\Facades\Facade;

class PoChat extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PoChat';
    }

}