<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chatWith(User $user)
    {
        $user_authenticated = \App\Models\User::find(auth()->id());

        // Searching if there are some chats between the user authenticated and the user requested
        // If there is a chat, it will be returned, otherwise a null will be returned
        $chat = $user_authenticated->chats()->wherehas('users', function ($q) use ($user) {
			$q->where('chat_user.user_id', $user->id);
		})->first();

        if(!$chat)
        {
            $chat = \App\Models\Chat::create([]);

            $chat->users()->sync([$user_authenticated->id, $user->id]);
        }

        return redirect()->route('chat.show', $chat);
    }

    public function show(Chat $chat)
    {

        // Error if the user do not belongs to this chat
        abort_unless($chat->users->contains(auth()->id()), 403);

        return view('chat', [
            'chat' => $chat
        ]);
    }

    public function getUsers(Chat $chat)
    {
        $users = $chat->users;

        return response()->json([
            'users' => $users
        ]);
    }

    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->get();

        return response()->json([
            'messages' => $messages
        ]);
    }
}
