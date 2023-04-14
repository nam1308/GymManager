@extends('layouts.cart')
@section('content')
  <div class="container">
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="/docs/4.2/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h2>{{$product->name}}</h2>
      <div class="text-left">
        <ul>
          <li> お申込みと名義の異なるクレジットカードを使用された場合、入会をお断りすることがございます。法人様の場合で、申込み時の会社名と名義が異なるカードをお使いの場合（代表取締役名義、経理担当者名義等）は、事前に、info@medu.biz
            までご連絡をお願い致します。
          </li>
          <li>領収書はクレジットカードのご利用明細にて代えさせていただいております。（確定申告をされる際には、クレジットカードのご利用明細が正式な証明書面となります。ご利用明細があれば、別途領収証を求められることはありません。）
          </li>
          <li>ご契約日より10日間はクーリングオフが適用されます。クーリングオフ期間後の契約期間中のキャンセルの返金はお受けできませんのご了承ください。キャンセルをご希望の際は、次回決済日までに所定の退会フォームより退会手続きを完了してください。
          </li>
        </ul>
      </div>
      @if (session('flash_message_success'))
        <div class="col-sm-12">
          <div class="flash_message bg-success text-center py-3 my-0">
            <span style="color: #fff; font-weight: bold">{{ session('flash_message_success') }}</span>
          </div>
        </div>
        <br>
      @endif
      @if (session('flash_message_danger'))
        <div class="col-sm-12">
          <div class="flash_message bg-danger text-center py-3 my-0">
            <span style="color: #fff; font-weight: bold">{{ session('flash_message_danger') }}</span>
          </div>
        </div>
        <br>
      @endif
      @if (session('flash_message_warning'))
        <div class="col-sm-12">
          <div class="flash_message bg-warning text-center py-3 my-0">
            <span style="color: #fff; font-weight: bold">{{ session('flash_message_warning') }}</span>
          </div>
        </div>
        <br>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach (array_unique($errors->all()) as $key => $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
    <div class="col">
      {{ Form::open(['url' => route('purchase.confirm', $product_code), 'class' => 'h-adr']) }}
      <div class="card">
        <div class="card-body">
          <input type="hidden" name="product_code" value="{{ $product_code }}">
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">姓名</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder="例）山田"
                           class="form-control form-control-lg @error('sei') is-invalid @enderror"
                           autofocus name="sei" type="text" value="{{ old('sei') }}">
                    @error('sei')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder="例）太郎"
                           class="form-control form-control-lg @error('mei') is-invalid @enderror"
                           name="mei" type="text" value="{{ old('mei') }}">
                    @error('mei')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">姓名（フリガナ）</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder="例）ヤマダ"
                           class="form-control form-control-lg @error('sei_kana') is-invalid @enderror"
                           name="sei_kana" type="text" value="{{ old('sei_kana') }}">
                    @error('sei_kana')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder="例）タロウ"
                           class="form-control form-control-lg @error('mei_kana') is-invalid @enderror"
                           name="mei_kana" type="text" value="{{ old('mei_kana') }}">
                    @error('mei_kana')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">メールアドレス（確認用）</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-8">
                  <div class="form-group">
                    <input placeholder="例）example@example.com"
                           class="form-control form-control-lg @error('email_confirmation') is-invalid @enderror"
                           name="email_confirmation" type="text"
                           value="{{ old('email_confirmation') }}">
                    @error('email_confirmation')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
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
            <label for="staticEmail" class="col-sm-2 col-form-label">パスワード（確認用）</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-8">
                  <div class="form-group">
                    <input placeholder=""
                           class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                           name="password_confirmation" type="password"
                           value="{{ old('password_confirmation') }}">
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong>
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
            <label for="staticEmail" class="col-sm-2 col-form-label">お支払方法</label>
            <div class="col-sm-10">
              @foreach ($payment_methods as $key => $val)
                <div class="form-check form-check-inline">
                  <input
                      class="form-check-input form-control-lg @error('payment_type_id') is-invalid @enderror"
                      type="radio" name="payment_type_id" id="payment_type_{{ $key }}"
                      value="{{ $key }}" @if ($key == old('payment_type_id')) checked @endif>
                  <label class="form-check-label" for="inlineRadio1">{{ $val }}</label>
                </div>
              @endforeach
              @error('payment_type_id')
              <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
              @enderror
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">本サービスをどこでお知りになりましたか？</label>
            <div class="col-sm-10">
              @foreach ($question_1 as $key => $val)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="question_1"
                         id="question_1_{{ $key }}" value="{{ $key }}">
                  <label class="form-check-label" for="exampleRadios1"> {{ $val }} </label>
                </div>
              @endforeach
              <br>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">紹介者</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder=""
                           class="form-control form-control-lg @error('introducer') is-invalid @enderror"
                           autofocus name="introducer" type="text" value="{{ old('introducer') }}">
                    @error('introducer')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">何に興味がありますか？</label>
            <div class="col-sm-10">
              @foreach ($question_2 as $key => $val)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="question_2[]"
                         id="question_2_{{ $key }}" value="{{ $key }}">
                  <label class="form-check-label" for="exampleRadios1"> {{ $val }} </label>
                </div>
              @endforeach
              <br>
            </div>
            <label for="staticEmail" class="col-sm-2 col-form-label">その他</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input placeholder=""
                           class="form-control form-control-lg @error('other') is-invalid @enderror"
                           autofocus id="other" name="other" type="text" value="{{ old('other') }}">
                    @error('other')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="price" value="{{$product->price}}">
        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-body">
          <h1>
            {{$product->view_price}}
          </h1>
        </div>
      </div>
      <br>
      {{ Form::submit('確認画面', ['class' => 'btn btn-success btn-lg btn-block']) }}
      {{ form::close() }}
      <footer class="my-5 pt-5 text-muted text-center text-small">
        <p>{{$basic_setting->company_name}} 投資助言・代理業　近畿財務局長（金商）第409号 加入協会：一般社団法人　日本投資顧問業協会　会員番号022‐00283</p>
        <p class="mb-1">&copy; 2020-2021 {{ $basic_setting->company_name }}</p>
        <ul class="list-inline">
          <li class="list-inline-item"><a href="#">プライバシーポリシー</a></li>
          <li class="list-inline-item"><a href="#">規約</a></li>
          <li class="list-inline-item"><a href="#">サポート</a></li>
        </ul>
      </footer>
    </div>
  </div>
@endsection
