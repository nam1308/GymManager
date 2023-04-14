@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.line-apply.create') }}
    @if(!$line_message)
      <div class="card" style="margin-bottom: 150px;">
        <div class="card-header">{{ __('LINE利用申請') }}</div>
        <div class="card-body">
          {{ Form::open(['url' => route('admin.line-apply.store'), 'class' => 'h-adr', 'enctype'=>"multipart/form-data"]) }}
          <span class="p-country-name" style="display:none;">Japan</span>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">チャンネル名 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input placeholder="例）○○ジムLINE"
                           class="form-control form-control-lg @error('channel_name') is-invalid @enderror"
                           autofocus
                           name="channel_name"
                           maxlength="20"
                           type="text"
                           value="{{ old('channel_name') }}">
                    <small>20文字以内</small>
                    @error('channel_name')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">チャンネル説明 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                      <textarea
                          id="channel_description"
                          class="form-control form-control-lg @error('channel_description') is-invalid @enderror"
                          name="channel_description"
                          type="text"
                          rows="5"
                          placeholder="詳細を記入してください">{{old('channel_description')}}</textarea>
                    @error('channel_description')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">チャンネルアイコン <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input
                        id="channel_icon"
                        class="form-control-file @error('channel_icon') is-invalid @enderror"
                        name="channel_icon"
                        type="file"
                        accept="image/*">
                    <p> 対応ファイル形式：PNG,JPG,JPEG,GIF
                      ファイルサイズ：3 MB以内</p>
                    @error('channel_icon')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">メールアドレス <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input placeholder="例）example@example.com"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           name="email"
                           type="text"
                           value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">ラインチャンネルで利用したいURL <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input placeholder="例）https://example.com"
                           class="form-control form-control-lg @error('store_url') is-invalid @enderror"
                           name="store_url"
                           type="url"
                           value="{{ old('store_url') }}">
                    <small>店舗URLやSNS（Twitter,Youtubeなど）のURLを登録してください。ラインチャンネルメニューで利用されます。</small>
                    @error('store_url')
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
                    <input placeholder="例）@435jses"
                           class="form-control form-control-lg @error('line_uri1') is-invalid @enderror"
                           name="line_uri1"
                           type="text"
                           value="{{ old('line_uri1') }}">
                    <small>
                      既に「ラインオフィシャルアカウントマネージャー」でアカウントを取得している場合はこちらに設定してください。<br>
                      お問い合わせチャンネルを設定するとラインチャンネルメニュー内で「お問合せ」チャットを設定することができます。
                      <br>
                      <strong>確認方法</strong><br>
                      <ul>
                        <li>
                          <a href="https://manager.line.biz/" target="_blank">ラインオフィシャルアカウントマネージャー</a>にログインします。
                        </li>
                        <li>
                          アカウントリストを選択します。
                        </li>
                        <li>
                          右上のある「設定」を選択します。
                        </li>
                        <li>
                          一番下にある「アカウント情報」の「ベーシックID」を設定してください。
                        </li>
                      </ul>
                    </small>
                    @error('line_uri1')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">プライバシーポリシーURL</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input placeholder="例）http://example.com"
                           class="form-control form-control-lg @error('privacy_policy_url') is-invalid @enderror"
                           name="privacy_policy_url"
                           type="url"
                           value="{{ old('privacy_policy_url') }}">
                    @error('privacy_policy_url')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">サービス利用規約URL</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input placeholder="例）https://example.com"
                           class="form-control form-control-lg @error('terms_of_use_url') is-invalid @enderror"
                           name="terms_of_use_url"
                           type="url"
                           value="{{ old('terms_of_use_url') }}">
                    @error('terms_of_use_url')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{ Form::submit('申請', ['class' => 'btn btn-success btn-lg btn-block']) }}
          {{ form::close() }}
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        申請中です
      </div>
    @endif
  </div>
@endsection
