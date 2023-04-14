@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ __('トレーナーログイン') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('admin.login') }}">
              @csrf
              <div class="form-group row">
                <label for="login_id" class="col-md-4 col-form-label text-md-right">{{ __('ログインID') }}</label>
                <div class="col-md-6">
                  <input
                      id="login_id"
                      type="tel"
                      class="form-control form-control-lg @error('login-id') is-invalid @enderror"
                      name="login_id" value="{{ old('login_id') }}"
                      placeholder="12345678"
                      required
                      autofocus>
                  @error('login-id')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('パスワード') }}</label>
                <div class="col-md-6">
                  <input
                      id="password"
                      type="password"
                      class="form-control form-control-lg @error('password') is-invalid @enderror"
                      name="password"
                      required>
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('ログイン') }}
                  </button>
                  @if (Route::has('admin.password.request'))
                    <a class="btn btn-link" href="{{ route('admin.password.request') }}">
                      {{ __('Forgot Your Password?') }}
                    </a>
                  @endif
                </div>
              </div>
              <a href="{{route('admin.forgot-password')}}">パスワードを忘れた方はこちら</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
