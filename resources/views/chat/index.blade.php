@extends('layouts.app')

@section('content')
<div class="row mb-0">
    <div class="col s3 chat-users-container">
        <h5>Usuarios</h5>
    </div>
    <div class="col s9 chat-container">
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