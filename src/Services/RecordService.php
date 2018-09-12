<?php

namespace Pokeface\Chating\Services;

use Carbon\Carbon;
use ChatRoute;
use PoChatChat;
use Pokeface\Chating\Models\ChatGroup;
use Pokeface\Chating\Models\ChatRecord;
use PoChatUser;

class RecordService
{
    public function getRecordByGroupId($group_ids,$created_at=''){
    	
        return ChatRecord::fetchByGroupId($group_ids,$created_at);
    }
}
