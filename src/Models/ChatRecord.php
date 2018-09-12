<?php
namespace Pokeface\Chating\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRecord extends Model
{

    public function __construct()
    {
        $this->setTable(config('pochat.table_names.chat_record'));
    }

    public static function fetchByGroupId($group_id,$created_at){
    	if(is_string($group_id) || is_int($group_id)){
    		$group_id = [$group_id];
    	}
    	$result = static::query()->whereIn('group_id',$group_id)->orderBy('created_at','desc')->limit(50);
    	if(!empty($created_at)){
    		$result->where('created_at','<',$created_at);
    	}
    	return $result->get();
    }
    
}