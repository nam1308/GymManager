@extends('layouts.super-admin')

@section('content')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <script type="module">
		window.addEventListener("DOMContentLoaded", function () {
			document.getElementById("phone_number").addEventListener("change", function () {
				var p = getFormatPhone(this.value);
				if (p) {
					this.value = p;
				}
			}, false);
		}, false);

  </script>
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.basic-setting.index',$data) }}
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="col">
          <div class="card">
            <div class="card-header"> {{ __('基本設定') }} </div>
            <div class="card-body">
              {{ Form::open(['url' => route('super-admin.basic-setting.update',['vendor_id'=>$data->vendor_id]), 'class' => 'h-adr']) }}
              @method('PUT')
              <span class="p-country-name" style="display:none;">Japan</span>
              <div class="form-group">
                <label for="">会社名 <span class="badge badge-danger">必須</span></label>
                <input placeholder=""
                       class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                       name="company_name" type="text"
                       value="{{ old('company_name', optional($data)->company_name) }}">
                @error('company_name')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              {{-- <div class="form-group">
                <label for="">会社名（カナ） <span class="badge badge-danger">必須</span></label>
                <input placeholder=""
                       class="form-control form-control-lg @error('company_name_kana') is-invalid @enderror"
                       name="company_name_kana" type="text"
                       value="{{ old('company_name_kana', optional($data)->company_name_kana) }}">
                @error('company_name_kana')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div> --}}
              {{-- <div class="form-group">
                <label for="">店名</label>
                <input placeholder=""
                       class="form-control form-control-lg @error('store_name') is-invalid @enderror"
                       name="store_name" type="text"
                       value="{{ old('store_name', optional($data)->store_name) }}">
                @error('store_name')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div> --}}
              {{-- <div class="form-group">
                <label for="">店名(カナ)</label>
                <input placeholder=""
                       class="form-control form-control-lg @error('store_name_kana') is-invalid @enderror"
                       name="store_name_kana" type="text"
                       value="{{ old('store_name_kana', optional($data)->store_name_kana) }}">
                @error('store_name_kana')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div> --}}
              {{-- <div class="form-group">
                <label for="">店名(英語表記)</label>
                <input placeholder=""
                       class="form-control form-control-lg @error('store_name_en') is-invalid @enderror"
                       name="store_name_en" type="text"
                       value="{{ old('store_name_en', optional($data)->store_name_en) }}">
                @error('store_name_en')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div> --}}
              <div class="form-group">
                <label for="">郵便番号 <span class="badge badge-danger">必須</span></label>〒 ※ハイフンなし
                <input placeholder="1234568"
                       class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                       size="8" maxlength="8" name="postal_code" type="text"
                       value="{{ old('postal_code', optional($data)->postal_code) }}">
                @error('postal_code')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="">都道府県 <span class="badge badge-danger">必須</span></label>
                <select
                    class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
                    name="prefecture_id">
                  <option value="">選択</option>
                  @foreach (prefectures() as $key => $val)
                    <option value="{{ $key }}" @if ($key == old('prefecture_id', optional($data)->prefecture_id)) selected @endif>
                      {{ $val }}
                    </option>
                  @endforeach
                </select>
                @error('prefecture_id')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="name">市区町村 <span class="badge badge-danger">必須</span></label>
                <input placeholder="例）千代田区" id="municipality"
                       class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                       name="municipality" type="text"
                       value="{{ old('municipality', optional($data)->municipality) }}">
                @error('municipality')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="name">番地・ビル名</label>
                <input placeholder="" id="address_building_name"
                       class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                       name="address_building_name"
                       value="{{ old('address_building_name', optional($data)->address_building_name) }}">
                @error('address_building_name')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="name">固定電話 <span class="badge badge-danger">必須</span></label>
                <input placeholder="例）03-1234-5678" id="phone_number"
                       class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                       name="phone_number"
                       value="{{ old('phone_number', optional($data)->phone_number) }}">
                @error('phone_number')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              {{-- <div class="form-group">
                <label for="">店舗営業時間</label>
                <input placeholder="" class="form-control form-control-lg" name="business_hours" type="text"
                       value="{{ old('business_hours', optional($data)->business_hours) }}">
                @error('business_hours')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div> --}}
              <div class="form-group">
                <label for="note_super_admin">備考</label>
                <textarea id="note_super_admin" name="note_super_admin" class="form-control form-control-lg" rows="5">{{ old('note_super_admin', optional($data)->note_super_admin) }}</textarea>
                @error('note_super_admin')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
              </div>
              <div class="mb-2">ブロック</div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="blockRadioYes" name="block" value="1" class="custom-control-input" @if ($isBlock)
                    checked
                @endif>
                <label class="custom-control-label" for="blockRadioYes">ON</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="blockRadioNo" name="block" value="0" class="custom-control-input" @if (!$isBlock)
                    checked
                @endif>
                <label class="custom-control-label" for="blockRadioNo">OFF</label>
              </div>
              <div class="mb-3"></div>
              <!-- 送信ボタン -->
              {{ Form::submit('保存する', ['class' => 'btn btn-primary btn-lg']) }}
              {{ form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
{{-- <div class="row">
                    <div class="col-sm-6">
                        {{ Form::open(['url' => route('super-admin.basic-setting.update'), 'class' => 'h-adr']) }}
                        <span class="p-country-name" style="display:none;">Japan</span>
                        @method('PUT')
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            {{ Form::label(null, '会社名') }}
                            {{ Form::text('company_name', $data ? $data->company_name : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '会社名（カナ）') }}
                            {{ Form::text('company_name_kana', $data ? $data->company_name_kana : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '店名') }}
                            {{ Form::text('store_name', $data ? $data->store_name : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '店名(カナ)') }}
                            {{ Form::text('store_name_kana', $data ? $data->store_name_kana : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '店名(英語表記)') }}
                            {{ Form::text('store_name_en', $data ? $data->store_name_en : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '郵便番号') }}〒 ※ハイフンなし
                            {{ Form::text('postal_code', $data ? $data->postal_code : null, ['placeholder' => '1234568', 'class' => 'form-control form-control-lg p-postal-code', 'size' => 8, 'maxlength' => 8]) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '都道府県') }}
                            {{ Form::select('prefectures', prefectures(), $data ? $data->prefectures : null, ['class' => 'form-control form-control-lg p-region-id']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '市区町村') }}
                            {{ Form::text('municipality', $data ? $data->municipality : null, ['placeholder' => '', 'class' => 'form-control form-control-lg p-locality p-street-address p-extended-address']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '番地・ビル名') }}
                            {{ Form::text('address_building_name', $data ? $data->address_building_name : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '電話番号') }}
                            {{ Form::text('phone_number', $data ? $data->phone_number : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label(null, '店舗営業時間') }}
                            {{ Form::text('business_hours', $data ? $data->business_hours : null, ['placeholder' => '', 'class' => 'form-control form-control-lg']) }}
                        </div>
                        <!-- 送信ボタン -->
                        {{ Form::submit('保存する', ['class' => 'btn btn-primary btn-lg']) }}
                        {{ form::close() }}
                    </div>
                    <div class="col-sm-6"></div>
                </div> --}}
