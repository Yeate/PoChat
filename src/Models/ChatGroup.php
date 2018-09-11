<?php
namespace Pokeface\Chating\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Pokeface\Chating\Models\ChatGroupMember;
class ChatGroup extends Model
{
    public $fillable = ['name'];
    public function __construct()
    {
        $this->setTable(config('pochat.table_names.chat_group'));
    }

    public function members()
    {
        return $this->hasMany(
            ChatGroupMember::class,
            'group_id',
            'id'
        );
    }

    public static function create(array $attribute=[]){
        $group_ids = ChatGroupMember::whereIn('user_id',$attribute['uids'])->having('num',count($attribute['uids']))->groupBy('group_id')->get([
                    DB::raw('group_id'),
                    DB::raw('count(group_id) as num'),
                 ])->pluck('group_id');

        $group_ids = ChatGroupMember::whereIn('group_id',$group_ids)->having('num',count($attribute['uids']))->groupBy('group_id')->get([
                    DB::raw('group_id'),
                    DB::raw('count(group_id) as num'),
                 ]);
        if($group_ids->count()){
            return $group_ids->pop()->group_id;
        }else{
            switch ($attribute['species']) {
                case 'private':
                    $name = 'private';
                    
                    break;
                case 'group':
                    $name = '';
                    
                    break;
            }
            $group = app(ChatGroup::class);
            $group->name=$name;
            $group -> save();
            foreach($attribute['uids'] as $value){
                $groupMember = app(ChatGroupMember::class);
                $groupMember->group_id=$group->id;
                $groupMember->user_id=$value;
                $groupMember->save();
            }
            
        }
        return $group->id;

    }
    
}