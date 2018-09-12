<?php 
namespace Pokeface\Chating\Facades;


use Illuminate\Support\Facades\Facade;

class RecordServiceFacade extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PoChatRecord';
    }

}