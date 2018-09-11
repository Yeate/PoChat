<?php
namespace Pokeface\Chating\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroupMember extends Model
{
	protected $fillable = ['group_id','user_id'];
    public function __construct()
    {
        $this->setTable(config('pochat.table_names.chat_group_member'));
    }
    
}