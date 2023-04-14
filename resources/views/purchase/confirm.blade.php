@extends('layouts.cart')
@section('content')
    <div class="container">
        <div class="py-5 text-center">
            <h2>{{$product->name}}</h2>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['url' => route('purchase.store', $product_code)]) }}
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">姓名</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['sei']}} {{$inputs['mei']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">姓名（カタカナ）</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['sei_kana']}} {{$inputs['mei_kana']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">メールアドレス</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['email']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">生年月日</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['birthday_year']}}年{{$inputs['birthday_month']}}月{{$inputs['birthday_day']}}日">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">性別</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{genders($inputs['gender_id'])}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">電話番号</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['phone_number_1']}}-{{$inputs['phone_number_2']}}-{{$inputs['phone_number_3']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">携帯番号</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['cellphone_number_1']}}-{{$inputs['cellphone_number_2']}}-{{$inputs['cellphone_number_3']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">郵便番号</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['postal_code']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">住所</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{$inputs['prefecture_id']}}{{$inputs['municipality']}}{{$inputs['address_building_name']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">お支払方法</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{payment_methods($inputs['payment_type_id'])}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">価格</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{number_format($inputs['price'])}}円">
                        </div>
                    </div>
                    <input type="hidden" name="product_code" value="{{ $product_code }}">
                    <input type="hidden" name="sei" value="{{$inputs['sei']}}">
                    <input type="hidden" name="mei" value="{{$inputs['mei']}}">
                    <input type="hidden" name="sei_kana" value="{{$inputs['sei_kana']}}">
                    <input type="hidden" name="mei_kana" value="{{$inputs['mei_kana']}}">
                    <input type="hidden" name="email" value="{{$inputs['email']}}">
                    <input type="hidden" name="email_confirmation" value="{{$inputs['email_confirmation']}}">
                    <input type="hidden" name="password" value="{{$inputs['password']}}">
                    <input type="hidden" name="password_confirmation" value="{{$inputs['password_confirmation']}}">
                    <input type="hidden" name="birthday_year" value="{{$inputs['birthday_year']}}">
                    <input type="hidden" name="birthday_month" value="{{$inputs['birthday_month']}}">
                    <input type="hidden" name="birthday_day" value="{{$inputs['birthday_day']}}">
                    <input type="hidden" name="gender_id" value="{{$inputs['gender_id']}}">
                    <input type="hidden" name="phone_number_1" value="{{$inputs['phone_number_1']}}">
                    <input type="hidden" name="phone_number_2" value="{{$inputs['phone_number_2']}}">
                    <input type="hidden" name="phone_number_3" value="{{$inputs['phone_number_3']}}">
                    <input type="hidden" name="cellphone_number_1" value="{{$inputs['cellphone_number_1']}}">
                    <input type="hidden" name="cellphone_number_2" value="{{$inputs['cellphone_number_2']}}">
                    <input type="hidden" name="cellphone_number_3" value="{{$inputs['cellphone_number_3']}}">
                    <input type="hidden" name="postal_code" value="{{$inputs['postal_code']}}">
                    <input type="hidden" name="prefecture_id" value="{{$inputs['prefecture_id']}}">
                    <input type="hidden" name="municipality" value="{{$inputs['municipality']}}">
                    <input type="hidden" name="address_building_name" value="{{$inputs['address_building_name']}}">
                    <input type="hidden" name="payment_type_id" value="{{$inputs['payment_type_id']}}">
                    <input type="hidden" name="introducer" value="{{$inputs['introducer']}}">
                    <input type="hidden" name="other" value="{{$inputs['other']}}">
                    <input type="hidden" name="price" value="{{$inputs['price']}}">
                    {{ Form::submit('購入画面にすすむ', ['class' => 'btn btn-success btn-lg btn-block']) }}
                    {{ form::close() }}
                </div>
            </div>
            <br>
        </div>
        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p>株式会社メデュ 投資助言・代理業　近畿財務局長（金商）第409号 加入協会：一般社団法人　日本投資顧問業協会　会員番号022‐00283</p>
            <p class="mb-1">&copy; 2020-2021 {{ $basic_setting->company_name }}</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">プライバシーポリシー</a></li>
                <li class="list-inline-item"><a href="#">規約</a></li>
                <li class="list-inline-item"><a href="#">サポート</a></li>
            </ul>
        </footer>
    </div>
@endsection
