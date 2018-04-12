@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col s10 m8 l6 offset-s1 offset-m2 offset-l3">
        <div class="card hoverable mt-75 mb-0">
            <div class="card-content">
                <div class="card-title center">{{ __('Reset Password') }}</div>

                <form method="POST" action="{{ route('password.request') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

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
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
