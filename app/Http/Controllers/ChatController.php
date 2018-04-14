<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use DB;

use App\Events\NewMessage;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '<>', auth()->id())->get();
        return view('chat.index', compact('users'));
    }

    public function getMessagesWith($user_id, Request $request)
    {
        $user_id = (int)$user_id;
        $my_id = auth()->id();

        if ($user_id === $my_id) {
            return [];
        }

        $messages = Message::where(function ($query) use ($my_id) {
            $query->where('from', $my_id)
                ->orWhere('to', $my_id);
        })
            ->where(function ($query) use ($user_id) {
                $query->where('from', $user_id)
                    ->orWhere('to', $user_id);
            })
            ->get(); //paginate
        return $messages->toJson();
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'to' => 'required|numeric',
            'body' => 'required'
        ]);

        $body = htmlentities($request->input('body'), ENT_QUOTES, 'UTF-8', false);

        $msg = Message::create([
            'from' => auth()->id(),
            'to' => $request->input('to'),
            'body' => $body
        ]);

        if ($msg) {
            broadcast(new NewMessage($msg))->toOthers();
            return $msg->toJson();
        } else {
            // TODO: tratar melhor problemas...
            return [];
        }
    }
}
