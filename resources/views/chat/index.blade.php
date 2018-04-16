@extends('layouts.app')

@section('content')
<audio id="chat-audio">
    <source src="{{ asset('sounds/chat.mp3') }}" type="audio/mpeg">
    <source src="{{ asset('sounds/chat.ogg') }}" type="audio/ogg" />
</audio>
<div class="row mb-0">
    <div id="chat-users-container" class="col l3 m1 s2">
        <h5 class="hide-on-med-and-down">Usuarios</h5>
        <ul data-my-userid="{{ auth()->id() }}">
            @foreach ($users as $user)
                <li class="col s12 chat-user" data-id="{{ $user->id }}">
                    <div class="row valign-wrapper mb-5 mt-5">
                    <div class="col m12 l2 valign-wrapper img-container tooltipped" data-position="right" data-tooltip="{{ $user->name }}">
                            <img src="http://via.placeholder.com/50x50" alt="" class="circle responsive-img">
                            <i class="material-icons tiny online-status-icon red-text">fiber_manual_record</i>
                        </div>
                        <div class="col s10 hide-on-med-and-down">
                            <div class="container-flex">
                                <div class="truncate">{{ ucwords($user->name) }}</div>
                                <div class="text-muted text-small last-message-date">
                                    {{isset($msgs[$user->id]) ? 
                                        $msgs[$user->id]->created_at->toDateString() : ""}}
                                </div>
                            </div>
                            <div class="container-flex">
                                <span class="truncate text-muted last-message">
                                    {!! isset($msgs[$user->id]) ? $msgs[$user->id]->body : "" !!}
                                </span>
                                <span class="round-badge blue white-text" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                </li>
            @endforeach
        </ul>
    </div>
    <div id="chat-container" class="col l9 m11 s10">
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