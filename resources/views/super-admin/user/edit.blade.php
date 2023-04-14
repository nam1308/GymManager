@extends('layouts.super-admin')

@section('content')
  <script type="module">
		window.addEventListener("DOMContentLoaded", function () {
			document.getElementById("phone_number").addEventListener("change", function () {
				var p = getFormatPhone(this.value);
				if (p) {
					this.value = p;
				}
			}, false);
			document.getElementById("cellphone_number").addEventListener("change", function () {
				var p = getFormatPhone(this.value);
				if (p) {
					this.value = p;
				}
			}, false);
		}, false);

  </script>
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.user.edit', $user) }}
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="col">
          <div class="card">
            <div class="card-header">{{ __('会員登録') }}</div>
            <div class="card-body">
              {{ Form::open(['url' => route('super-admin.user.update', $user->id), 'class' => 'h-adr']) }}
              @method('put')
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">姓 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）山田"
                           class="form-control form-control-lg @error('sei') is-invalid @enderror"
                           autofocus name="sei" type="text"
                           value="{{ old('sei', optional($user)->sei) }}">
                    @error('sei')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">名 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）太郎"
                           class="form-control form-control-lg @error('mei') is-invalid @enderror"
                           name="mei" type="text" value="{{ old('mei', optional($user)->mei) }}">
                    @error('mei')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">セイ <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）ヤマダ"
                           class="form-control form-control-lg @error('sei_kana') is-invalid @enderror"
                           name="sei_kana" type="text"
                           value="{{ old('sei_kana', optional($user)->sei_kana) }}">
                    @error('sei_kana')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">メイ <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）タロウ"
                           class="form-control form-control-lg @error('mei_kana') is-invalid @enderror"
                           name="mei_kana" type="text"
                           value="{{ old('mei_kana', optional($user)->mei_kana) }}">
                    @error('mei_kana')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">年 <span class="badge badge-danger">必須</span></label>
                    <select
                        class="form-control form-control-lg @error('birthday_year') is-invalid @enderror"
                        name="birthday_year">
                      <option value="">選択</option>
                      @foreach (years() as $val)
                        <option value="{{ $val }}" @if ($val == old('birthday_year', optional($user)->view_birthday_year)) selected @endif>{{ $val }}年</option>
                      @endforeach
                    </select>
                    @error('birthday_year')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">月 <span class="badge badge-danger">必須</span></label>
                    <select
                        class="form-control form-control-lg @error('birthday_month') is-invalid @enderror"
                        name="birthday_month">
                      <option value="">選択</option>
                      @foreach (months() as $val)
                        <option value="{{ $val }}" @if ($val == old('birthday_month', optional($user)->view_birthday_month)) selected @endif>{{ $val }}月</option>
                      @endforeach
                    </select>
                    @error('birthday_month')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">日 <span class="badge badge-danger">必須</span></label>
                    <select
                        class="form-control form-control-lg @error('birthday_day') is-invalid @enderror"
                        name="birthday_day">
                      <option value="">選択</option>
                      @foreach (days() as $val)
                        <option value="{{ $val }}" @if ($val == old('birthday_day', optional($user)->view_birthday_day)) selected @endif>{{ $val }}日</option>
                      @endforeach
                    </select>
                    @error('birthday_day')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">性別 <span class="badge badge-danger">必須</span></label>
                    <select
                        class="form-control form-control-lg @error('gender_id') is-invalid @enderror"
                        name="gender_id">
                      <option value="">選択</option>
                      @foreach (genders() as $key => $val)
                        <option value="{{ $key }}" @if ($key == old('gender_id', optional($user)->gender_id)) selected @endif>{{ $val }}</option>
                      @endforeach
                    </select>
                    @error('gender_id')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">固定電話 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）03-1234-5678" id="phone_number"
                           class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                           name="phone_number" type="tel"
                           value="{{ old('phone_number', optional($user)->view_phone_number) }}">
                    @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">携帯電話</label>
                    <input placeholder="例）080-1234-5678" id="cellphone_number"
                           class="form-control form-control-lg @error('cellphone_number') is-invalid @enderror"
                           name="cellphone_number" type="tel"
                           value="{{ old('cellphone_number', optional($user)->view_cellphone_number) }}">
                    @error('cellphone_number')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                {{-- <div class="col-6">
                    <div class="form-group">
                        <label for="name">メールアドレス <span class="badge badge-danger">必須</span></label>
                        <input placeholder="例）example@example.com"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            name="email" type="text" value="{{ old('email', optional($user)->email) }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> --}}
                {{-- <div class="col-6">
                    <div class="form-group">
                        <label for="password">パスワード <span class="badge badge-danger">必須</span></label>
                        <input id="password" type="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            name="password" value="{{ old('password') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> --}}
              </div>
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">郵便番号 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）111-2222" id="postal_code"
                           class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                           size="8" maxlength="8" name="postal_code" type="text"
                           value="{{ old('postal_code', optional($user)->postal_code) }}">
                    @error('postal_code')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">都道府県 <span class="badge badge-danger">必須</span></label>
                    <select
                        class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
                        name="prefecture_id">
                      <option value="">選択</option>
                      @foreach (prefectures() as $key => $val)
                        <option value="{{ $key }}" @if ($key == old('prefecture_id', optional($user)->prefecture_id)) selected @endif>{{ $val }}</option>
                      @endforeach
                    </select>
                    @error('prefecture_id')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="name">市区町村 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）千代田区" id="municipality"
                           class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                           name="municipality"
                           value="{{ old('municipality', optional($user)->municipality) }}">
                    @error('municipality')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="name">番地・ビル名</label>
                    <input placeholder="" id="address_building_name"
                           class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                           name="address_building_name"
                           value="{{ old('address_building_name', optional($user)->address_building_name) }}">
                    @error('address_building_name')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    {{ Form::label(null, '会社名') }}
                    <input placeholder=""
                           class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                           name="company_name" type="text"
                           value="{{ old('company_name', optional($user)->company_name) }}">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                  </div>
                </div>
              </div>
              {{ Form::submit('保存する', ['class' => 'btn btn-primary btn-lg']) }}
              {{ form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
