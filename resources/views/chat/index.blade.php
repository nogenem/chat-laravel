@extends('layouts.app')

@section('content')
<div class="row mb-0">
    <div id="chat-users-container" class="col l3 m1 s2">
        <h5 class="hide-on-med-and-down">Usuarios</h5>
        <ul>
            @foreach ($users as $user)
                <li class="col s12 chat-user" data-id="{{ $user->id }}">
                    <div class="row valign-wrapper mb-5 mt-5">
                        <div class="col m12 l2 valign-wrapper img-container">
                            <img src="http://via.placeholder.com/50x50" alt="" class="circle responsive-img">
                        </div>
                        <div class="col s10 hide-on-med-and-down">
                            <div class="container-flex">
                                <div class="truncate">{{ ucwords($user->name) }}</div>
                                <div class="text-muted text-small">12/12/2018</div>
                            </div>
                            <div class="container-flex">
                                <span class="truncate text-muted">Ultima mensagem enviada</span>
                                <span class="round-badge blue white-text">4</span>
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
                            <input id="chat-message" type="text" name="message" class="mb-0" placeholder="Digite uma mensagem aqui" required autofocus>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection