<?php

namespace App\Broadcasting;

use App\User;

class ChatChannel
{
    public function join(User $user , $id)
    {
        return $user->id==$id;
    }
}
