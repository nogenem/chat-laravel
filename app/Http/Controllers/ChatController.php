<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Group;
use DB;

use App\Events\NewMessage;

class ChatController extends Controller
{

    private $msgsPerPage = 50;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $myId = $user->id;
        $groups = $user->groups;
        $myGroupsIds = $groups->map(function ($group) {
            return $group->id;
        });

        $users = User::where('id', '<>', $myId)->get();

        $userMsgs = Message::getLatestMessagesWithUser($myId);
        $groupMsgs = Message::getLatestMessagesWithGroups($myId, $myGroupsIds);

        return view('chat.index', compact('groups', 'users', 'userMsgs', 'groupMsgs'));
    }

    public function getMessagesWithUser($user_id, Request $request)
    {
        $user_id = (int)$user_id;
        $my_id = auth()->id();

        // Verifica se o usuário ta tentando pergar mensagens
        // dele para ele mesmo
        if ($user_id === $my_id) {
            // TODO: Tratar isso melhor!
            return [];
        }

        $messages = Message::where(function ($query) use ($my_id) {
            $query->where('from', $my_id)
                ->orWhere(function ($query) use ($my_id) {
                    $query->where('to_id', $my_id)
                        ->where('to_type', Message::$USER_TYPE);
                });
        })
            ->where(function ($query) use ($user_id) {
                $query->where('from', $user_id)
                    ->orWhere(function ($query) use ($user_id) {
                        $query->where('to_id', $user_id)
                            ->where('to_type', Message::$USER_TYPE);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->msgsPerPage);

        return $messages->toJson();
    }

    public function getMessagesWithGroup($group_id, Request $request)
    {
        $group_id = (int)$group_id;
        $user = auth()->user();
        $my_id = $user->id;

        if (!$user->belongsToGroup($group_id)) {
            // TODO: Tratar isso melhor!
            return [];
        }

        $messages = Message::where('to_id', $group_id)
            ->where('to_type', Message::$GROUP_TYPE)
            ->orderBy('created_at', 'desc')
            ->paginate($this->msgsPerPage);

        return $messages->toJson();
    }

    public function sendMessage(Request $request)
    {
        $user_type = Message::$USER_TYPE;
        $group_type = Message::$GROUP_TYPE;

        $request->validate([
            'to_id' => 'required|numeric',
            'to_type' => "required|in:$user_type,$group_type",
            'body' => 'required'
        ]);

        $to_id = (int)$request->input('to_id');
        $to_type = $request->input('to_type');

        if ($to_type === $group_type) {
            $user = auth()->user();
            if (!$user->belongsToGroup($to_id)) {
                // TODO: Tratar isso melhor!
                return [];
            }
        }

        $body = htmlentities($request->input('body'), ENT_QUOTES, 'UTF-8', false);

        $msg = Message::create([
            'from' => auth()->id(),
            'to_id' => $to_id,
            'to_type' => $to_type,
            'body' => $body
        ]);

        if ($msg) {
            $groupUsersIds = null;
            if ($to_type === $group_type) {
                $group = Group::with('users')->find($to_id);
                $groupUsersIds = $group->users->map(function ($user) {
                    return $user->id;
                });
            }
            broadcast(new NewMessage($msg, $groupUsersIds))->toOthers();
            return $msg->toJson();
        } else {
            // TODO: tratar melhor problemas...
            return [];
        }
    }
}
