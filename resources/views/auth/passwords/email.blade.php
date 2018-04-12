@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3">
        <div class="card hoverable mt-75 mb-0">
            <div class="card-content">
                <div class="card-title center">{{ __('Reset Password') }}</div>

                @if (session('status'))
                    <div class="card-panel green p-15">
                        <span class="white-text">{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
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
                    <div class="row mb-0">
                        <div class="col s12 center">
                            <button type="submit" class="btn waves-effect waves-light" style="width: 40%;">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
