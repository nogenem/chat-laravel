@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3">
        <div class="card hoverable mt-75 mb-0">
            <div class="card-content">
                <div class="card-title center">{{ __('Login') }}</div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" required autofocus>
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
                        <label class="col s12">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                            <span>{{ __('Remember Me') }}</span>
                        </label>
                    </div>
                    <div class="row mb-0">
                        <div class="col s12 center">
                            <button type="submit" class="btn waves-effect waves-light" style="width: 40%;">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mt-0">
    <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3 center">
        <a class="font-bold text-underlined teal-text" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    </div>
</div>
@endsection
