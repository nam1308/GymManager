@extends('layouts.admin')

@section('content')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <div class="container-fluid">
    {{ Breadcrumbs::render('admin.trainer.create') }}
    <div class="container">
      <div class="col">
        <div class="card">
          <div class="card-header">{{ __('トレーナー登録') }}</div>
          <div class="card-body">
            {{ Form::open(['url' => route('admin.user.store'), 'class' => 'h-adr']) }}
            <span class="p-country-name" style="display:none;">Japan</span>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">名前</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <input placeholder="例）山田太郎"
                             class="form-control form-control-lg @error('name') is-invalid @enderror"
                             autofocus name="name"
                             type="text"
                             value="{{ old('name') }}">
                      @error('name')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">メールアドレス</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-8">
                    <div class="form-group">
                      <input placeholder="例）example@example.com"
                             class="form-control form-control-lg @error('email') is-invalid @enderror"
                             name="email" type="text" value="{{ old('email') }}">
                      @error('email')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">パスワード</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-8">
                    <div class="form-group">
                      <input placeholder=""
                             class="form-control form-control-lg @error('password') is-invalid @enderror"
                             name="password" type="password" value="{{ old('password') }}">
                      @error('password')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">生年月日</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <select
                          class="form-control form-control-lg @error('birthday_year') is-invalid @enderror"
                          name="birthday_year">
                        <option value="">選択</option>
                        @foreach (years() as $val)
                          <option value="{{ $val }}" @if ($val == old('birthday_year', 1978)) selected @endif>{{ $val }}年</option>
                        @endforeach
                      </select>
                      @error('birthday_year')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <select
                          class="form-control form-control-lg @error('birthday_month') is-invalid @enderror"
                          name="birthday_month">
                        <option value="">選択</option>
                        @foreach (months() as $val)
                          <option value="{{ $val }}" @if ($val == old('birthday_month')) selected @endif>{{ $val }}月</option>
                        @endforeach
                      </select>
                      @error('birthday_month')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <select
                          class="form-control form-control-lg @error('birthday_day') is-invalid @enderror"
                          name="birthday_day">
                        <option value="">選択</option>
                        @foreach (days() as $val)
                          <option value="{{ $val }}" @if ($val == old('birthday_day')) selected @endif>{{ $val }}日</option>
                        @endforeach
                      </select>
                      @error('birthday_day')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label ">性別</label>
              <div class="col-sm-10">
                @foreach (genders() as $key => $val)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input form-control-lg @error('gender_id') is-invalid @enderror"
                           type="radio" name="gender_id" id="gender_{{ $key }}"
                           value="{{ $key }}" @if ($key == old('gender_id')) checked @endif>
                    <label class="form-check-label" for="inlineRadio1">{{ $val }}</label>
                  </div>
                @endforeach
                @error('gender_id')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">電話番号</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）03" id="phone_number"
                             class="form-control form-control-lg @error('phone_number_1') is-invalid @enderror"
                             name="phone_number_1" type="tel" value="{{ old('phone_number_1') }}">
                      @error('phone_number_1')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）1234" id="phone_number_2"
                             class="form-control form-control-lg @error('phone_number_2') is-invalid @enderror"
                             name="phone_number_2" type="tel" value="{{ old('phone_number_2') }}">
                      @error('phone_number_2')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）5678" id="phone_number_3"
                             class="form-control form-control-lg @error('phone_number_3') is-invalid @enderror"
                             name="phone_number_3" type="tel" value="{{ old('phone_number_3') }}">
                      @error('phone_number_3')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">携帯番号</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）090" id="cellphone_number_1"
                             class="form-control form-control-lg @error('cellphone_number_1') is-invalid @enderror"
                             name="cellphone_number_1" type="tel" value="{{ old('cellphone_number_1') }}">
                      @error('cellphone_number_1')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）1234" id="cellphone_number_2"
                             class="form-control form-control-lg @error('cellphone_number_2') is-invalid @enderror"
                             name="cellphone_number_2" type="tel" value="{{ old('cellphone_number_2') }}">
                      @error('cellphone_number_2')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）5678" id="cellphone_number_3"
                             class="form-control form-control-lg @error('cellphone_number_3') is-invalid @enderror"
                             name="cellphone_number_3" type="tel" value="{{ old('cellphone_number_3') }}">
                      @error('cellphone_number_3')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">郵便番号</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <input placeholder="例）1112222" id="postal_code"
                             class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                             size="8" maxlength="8" name="postal_code" type="text"
                             value="{{ old('postal_code') }}">
                      @error('postal_code')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">都道府県</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <select
                          class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
                          name="prefecture_id">
                        <option value="">選択</option>
                        @foreach (prefectures() as $key => $val)
                          <option value="{{ $key }}" @if ($key == old('prefecture_id')) selected @endif>{{ $val }}</option>
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
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">市区町村</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <input placeholder="例）千代田区" id="municipality"
                             class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                             name="municipality" value="{{ old('municipality') }}">
                      @error('municipality')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">番地・ビル名</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <input placeholder="" id="address_building_name"
                             class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                             name="address_building_name"
                             value="{{ old('address_building_name') }}">
                      @error('address_building_name')
                      <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              {{ Form::submit('会員登録', ['class' => 'btn btn-success btn-lg btn-block']) }}
              {{ form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
