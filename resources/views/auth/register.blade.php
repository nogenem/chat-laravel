@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3">
        <div class="card hoverable">
            <div class="card-content">
                <div class="card-title center">{{ __('Register') }}</div>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="name" type="text" class="validate" name="name" value="{{ old('name') }}" required autofocus>
                            <label for="name">{{ __('Name') }}</label>
                            
                            @if ($errors->has('name'))
                                <span class="helper-text red-text font-bold">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" required>
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            
                            @if ($errors->has('email'))
                                <span class="helper-text red-text font-bold">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="password" class="validate" name="password" required>
                            <label for="password">{{ __('Password') }}</label>

                            @if ($errors->has('password'))
                                <span class="helper-text red-text font-bold">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password-confirm" type="password" class="validate" name="password_confirmation" required>
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>

                            @if ($errors->has('password_confirmation'))
                                <span class="helper-text red-text font-bold">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col s12 center">
                            <button type="submit" class="btn waves-effect waves-light" style="width: 40%;">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
