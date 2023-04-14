@extends('layouts.super-admin')
@section('content')
  <div class="container-fluid">
    {{--        {{ Breadcrumbs::render('admin.line-apply.create') }}--}}
    <div class="container">
      <div class="col">
        <div class="card">
          <div class="card-header">{{ __('LINEメッセージ情報入力') }}</div>
          <div class="card-body">
            <form method="POST" action="{{route('super-admin.line-apply.message-update', $line_message->vendor_id)}}" accept-charset="UTF-8" class="h-adr" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">チャネルID <span class="badge badge-danger">必須</span></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input placeholder="例）1234567890"
                               class="form-control form-control-lg @error('channel_id') is-invalid @enderror"
                               autofocus
                               name="channel_id"
                               type="text"
                               pattern="(0|[1-9][0-9]*)"
                               value="{{ old('channel_id', $line_message->channel_id) }}" required>
                        @error('channel_id')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">チャンネルシークレット <span class="badge badge-danger">必須</span></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-8">
                      <div class="form-group">
                        <input class="form-control form-control-lg @error('channel_secret') is-invalid @enderror"
                               name="channel_secret"
                               type="text"
                               value="{{ old('channel_secret', $line_message->channel_secret) }}">
                        @error('channel_secret')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">チャネルアクセストークン <span class="badge badge-danger">必須</span></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input class="form-control form-control-lg @error('channel_access_token') is-invalid @enderror"
                               name="channel_access_token"
                               type="text"
                               value="{{ old('channel_access_token', $line_message->channel_access_token) }}">
                        @error('channel_access_token')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">お問い合わせチャンネル</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input class="form-control form-control-lg @error('line_uri1') is-invalid @enderror"
                               name="line_uri1"
                               type="text"
                               value="{{ old('line_uri1', $line_message->line_uri1) }}">
                        <small class="text-danger">お問い合わせチャネルを変更した場合は必ず、スマフォでチャンネルに入って「delete」を入力して「create」を実行してください。このコマンドを実行しないとメニューを変更することができません。<br>そして必ずお問い合わせメニューが立ち上がるかの確認もしてください</small>
                        @error('line_uri1')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <label class="col-sm-2 col-form-label">QRコード <span class="badge badge-danger">必須</span></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <img alt="" src="{{$line_message->getQrCodeUrl()}}" width="200" height="200">
                        <input name="qr_code"
                               type="file"
                               class="form-control-file @error('qr_code') is-invalid @enderror">
                        <p> 対応ファイル形式：PNG,JPG,JPEG,GIF
                          ファイルサイズ：5MB以内</p>
                        @error('qr_code')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input class="btn btn-success btn-lg btn-block" type="submit" value="保存">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
