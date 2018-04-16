<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Message extends Model
{
    protected $fillable = [
        'from', 'to_id', 'to_type', 'body'
    ];

    public function to()
    {
        return $this->morphTo();
    }

    public static function getLatestMessagesWithUser($userId)
    {
        // Versão 'raw', não converte para model Message...
        // $ret = DB::select('
        //     select m1.* from messages m1
        //     inner join (select max(m.created_at) as latest, m.from from messages m where m.to = ? and m.from <> ? group by m.from) m2
        //     on m1.from = m2.from and m1.created_at = m2.latest
        //     where m1.to = ? and m1.from <> ?
        // ', [$myId, $myId, $myId, $myId]);

        // $userId vem do próprio Laravel então não deve ter problema de usar
        // com DB::raw()...
        $ret = static::join(DB::raw("
            (select max(m.created_at) as latest, m.from from messages m where m.to_id = $userId and m.to_type = 'App.User' and m.from <> $userId group by m.from) m2
        "), function ($query) {
            $query->on('messages.from', 'm2.from')->on('messages.created_at', 'm2.latest');
        })
            ->where('messages.to_id', $userId)
            ->where('messages.to_type', 'App.User')
            ->where('messages.from', "<>", $userId)
            ->get();

        $msgs = collect();
        foreach ($ret as $msg) {
            $msgs->put($msg->from, $msg);
        }

        return $msgs;
    }

    public static function getLatestMessagesWithGroups($userId, $groupsIds)
    {
        $msgs = collect();

        if ($groupsIds->isEmpty())
            return $msgs;

        $groupsIdsStr = '(' . $groupsIds->implode(',') . ')';
        $ret = static::join(DB::raw("
            (select max(m.created_at) as latest, m.to_id from messages m where m.to_id IN $groupsIdsStr and m.to_type = 'App.Group' and m.from <> $userId group by m.to_id) m2
        "), function ($query) {
            $query->on('messages.to_id', 'm2.to_id')->on('messages.created_at', 'm2.latest');
        })
            ->whereIn('messages.to_id', $groupsIds)
            ->where('messages.to_type', 'App.Group')
            ->where('messages.from', "<>", $userId)
            ->get();

        foreach ($ret as $msg) {
            $msgs->put($msg->to_id, $msg);
        }

        return $msgs;
    }
}
