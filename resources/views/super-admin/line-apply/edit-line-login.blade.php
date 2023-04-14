@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{--        {{ Breadcrumbs::render('admin.line-apply.create') }}--}}
    <div class="container">
      <div class="col">
        <div class="card">
          <div class="card-header">{{ __('LINEログイン情報入力') }}</div>
          <div class="card-body">
            {{ Form::open(['url' => route('super-admin.line-apply.login-update', $line_login_apply->id), 'class' => 'h-adr']) }}
            @method('PUT')
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">ログインチャンネルID</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <input placeholder="例）1234567890"
                             class="form-control form-control-lg @error('channel_id') is-invalid @enderror"
                             autofocus name="channel_id"
                             type="text"
                             pattern="(0|[1-9][0-9]*)"
                             value="{{ old('channel_id') }}" required>
                      @error('channel_id')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label class="col-sm-2 col-form-label">ログインチャンネルシークレット</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-8">
                    <div class="form-group">
                      <input class="form-control form-control-lg @error('channel_secret') is-invalid @enderror"
                             name="channel_secret" type="text" value="{{ old('channel_secret') }}">
                      @error('channel_secret')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              {{ Form::submit('保存', ['class' => 'btn btn-success btn-lg btn-block']) }}
              {{ form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
