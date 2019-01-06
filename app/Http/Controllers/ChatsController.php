<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\{User , Chat};

class ChatsController extends Controller
{
	public function index(){
		$users=User::all();
		return view('chat.users' , compact('users'));
	}

	public function chat(User $user){
		return view('chat.chat' , compact('user'));
	}

	public function send(){
	    $chat=auth()->user()->chats()->create([
            'receiver'=>request()->receiver,
            'message'=>request()->message
        ]);
		broadcast(new ChatEvent($chat , $chat->receiver));
	}
}
