@extends('layouts.welcome')
@section('content')
  <div class="container">
    @if($invitation)
      <div class="card">
        <div class="card-header">{{ __('トレーナー新規登録') }}</div>
        <div class="card-body">
          <form method="POST" action="{{route('admin.register.store', $invitation->token)}}" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div class="form-group row">
              <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('プロフィール画像') }} <span class="badge badge-danger">必須</span></label>
              <div class="col-md-7">
                <img class="rounded-circle" src="{{config('const.PROFILE_ICON')}}" alt="profile" width="100" height="100">
                <input name="profile_photo"
                       type="file"
                       class="form-control-file @error('profile_photo') is-invalid @enderror">
                <p> 対応ファイル形式：PNG,JPG,JPEG,GIF
                  ファイルサイズ：5MB以内</p>
                @error('profile_photo')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('お名前') }} <span class="badge badge-danger">必須</span></label>
              <div class="col-md-7">
                <input id="name"
                       class="form-control form-control-lg p-postal-code @error('name') is-invalid @enderror"
                       name="name"
                       type="text"
                       value="{{ old('name') }}">
                @error('name')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('自己紹介') }} <span class="badge badge-danger">必須</span></label>
              <div class="col-md-7">
                    <textarea
                        class="form-control form-control-lg @error('self_introduction') is-invalid @enderror"
                        name="self_introduction"
                        rows="5">{{ old('self_introduction') }}</textarea>
                @error('self_introduction')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('パスワード') }} <span class="badge badge-danger">必須</span></label>
              <div class="col-md-7">
                <input
                    id="password"
                    type="password"
                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                    name="password"
                    autocomplete="current-password">
                @error('password')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('パスワード（確認）') }} <span class="badge badge-danger">必須</span></label>
              <div class="col-md-7">
                <input
                    id="password_confirmation"
                    type="password"
                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation"
                    autocomplete="password_confirmation">
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-9 offset-md-3">
                <button type="submit" class="btn btn-primary"> {{ __('トレーナー新規登録') }} </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        トークンが有効期限です。
      </div>
    @endif
  </div>
@endsection
