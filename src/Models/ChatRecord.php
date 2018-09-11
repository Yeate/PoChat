<?php
namespace Pokeface\Chating\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRecord extends Model
{

    public function __construct()
    {
        $this->setTable(config('pochat.table_names.chat_record'));
    }
    
}