@extends('layouts.app')

@section('content')
<audio id="chat-audio">
    <source src="{{ asset('sounds/chat.mp3') }}" type="audio/mpeg">
    <source src="{{ asset('sounds/chat.ogg') }}" type="audio/ogg" />
</audio>
<div class="row mb-0">
    <div id="chat-users-container" data-my-userid="{{ auth()->id() }}" class="col l3 s1">
        <div class="row">
            <h5 class="hide-on-med-and-down">Grupos</h5>
            <ul>
                @foreach ($groups as $group)
                    <li class="col s12 chat-user" data-group-id="{{ $group->id }}">
                        <div class="col s12 l2 chat-img group-img">
                            <span class="round-badge blue white-text hide-on-large-only" style="visibility: hidden;"></span>
                        </div>
                        <div class="col l10 hide-on-med-and-down">
                            <div class="container-flex">
                                <span class="truncate font-bold">{{ ucwords($group->name) }}</span>
                                <div class="text-muted text-small last-message-date">
                                    {{isset($groupMsgs[$group->id]) ? 
                                        $groupMsgs[$group->id]->created_at->toDateString() : ""}}
                                </div>
                            </div>
                            <div class="container-flex">
                                <span class="truncate text-muted last-message">
                                    {!! isset($groupMsgs[$group->id]) ? $groupMsgs[$group->id]->body : "" !!}
                                </span>
                                <span class="round-badge blue white-text" style="visibility: hidden;"></span>
                            </div>
                        </div>
                    </li>
                    <li class="col s12 divider"></li>
                @endforeach
            </ul>
        </div>

        <div class="row">
            <h5 class="hide-on-med-and-down">Usuários</h5>
            <ul>
                @foreach ($users as $user)
                    <li class="col s12 chat-user" data-user-id="{{ $user->id }}">
                        <div class="col m12 l2 chat-img user-img">
                            <span class="red white-text status-badge"></span>
                            <span class="round-badge blue white-text hide-on-large-only" style="visibility: hidden;"></span>
                        </div>
                        <div class="col l10 hide-on-med-and-down">
                            <div class="container-flex">
                                <span class="truncate font-bold">{{ ucwords($user->name) }}</span>
                                <div class="text-muted text-small last-message-date">
                                    {{isset($userMsgs[$user->id]) ? 
                                        $userMsgs[$user->id]->created_at->toDateString() : ""}}
                                </div>
                            </div>
                            <div class="container-flex">
                                <span class="truncate text-muted last-message">
                                    {!! isset($userMsgs[$user->id]) ? $userMsgs[$user->id]->body : "" !!}
                                </span>
                                <span class="round-badge blue white-text" style="visibility: hidden;"></span>
                            </div>
                        </div>
                    </li>
                    <li class="col s12 divider"></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div id="chat-container" class="col l9 s11">
        <div id="chat-row" class="row mb-0" style="height: 80vh; overflow-y: auto;">
            <div class="col s12">
                <ul class="chat"></ul>
            </div>
        </div>
        <div class="row mb-0">
            <form method="POST" action="" class="col s12">
                @csrf

                <div class="row mb-0">
                    <div class="col s12">
                        <div class="input-field m-0">
                            <textarea id="chat-message" name="message" class="materialize-textarea mb-0" placeholder="Digite uma mensagem aqui" required autofocus rows="1"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection