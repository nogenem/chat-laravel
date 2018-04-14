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
        $myId = auth()->id();
        $users = User::where('id', '<>', $myId)->get();

        // Versão 'raw', não converte para model Message...
        // $ret = DB::select('
        //     select m1.* from messages m1
        //     inner join (select max(m.created_at) as latest, m.from from messages m where m.to = ? and m.from <> ? group by m.from) m2
        //     on m1.from = m2.from and m1.created_at = m2.latest
        //     where m1.to = ? and m1.from <> ?
        // ', [$myId, $myId, $myId, $myId]);

        // $myId vem do próprio Laravel então não deve ter problema de usar
        // com DB::raw()...
        $ret = Message::join(DB::raw("
            (select max(m.created_at) as latest, m.from from messages m where m.to = $myId and m.from <> $myId group by m.from) m2
        "), function ($query) {
            $query->on('messages.from', 'm2.from')->on('messages.created_at', 'm2.latest');
        })
            ->where('messages.to', $myId)
            ->where('messages.from', "<>", $myId)
            ->get();

        $msgs = collect();
        foreach ($ret as $msg) {
            $msgs->put($msg->from, $msg);
        }

        return view('chat.index', compact('users', 'msgs'));
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
