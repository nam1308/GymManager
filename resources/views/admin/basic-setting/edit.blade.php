@extends('layouts.admin')

@section('content')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <div class="container">
    {{ Breadcrumbs::render('admin.basic-setting.edit') }}
    <div class="card">
      <div class="card-header"> {{ __('会社設定') }} </div>
      <div class="card-body">
        {{ Form::open(['url' => route('admin.basic-setting.update'), 'class' => 'h-adr']) }}
        @method('PUT')
        <span class="p-country-name" style="display:none;">Japan</span>
        <div class="form-group">
          <label for="">会社名 <span class="badge badge-danger">必須</span></label>
          <input placeholder=""
                 class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                 name="company_name"
                 type="text"
                 value="{{ old('company_name', optional($basic_setting)->company_name) }}">
          @error('company_name')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="">郵便番号 <span class="badge badge-danger">必須</span></label>〒 ※ハイフンなし
          <input placeholder="1234568"
                 class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                 size="8"
                 maxlength="8"
                 name="postal_code"
                 type="text"
                 value="{{ old('postal_code', optional($basic_setting)->postal_code) }}">
          @error('postal_code')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="">都道府県 <span class="badge badge-danger">必須</span></label>
          <select
              class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
              name="prefecture_id">
            <option value="">選択</option>
            @foreach (prefectures() as $key => $val)
              <option value="{{ $key }}" @if ($key == old('prefecture_id', optional($basic_setting)->prefecture_id)) selected @endif>
                {{ $val }}
              </option>
            @endforeach
          </select>
          @error('prefecture_id')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">市区町村 <span class="badge badge-danger">必須</span></label>
          <input placeholder="例）千代田区" id="municipality"
                 class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                 name="municipality"
                 type="text"
                 value="{{ old('municipality', optional($basic_setting)->municipality) }}">
          @error('municipality')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">番地・ビル名</label>
          <input placeholder="" id="address_building_name"
                 class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                 name="address_building_name"
                 type="text"
                 value="{{ old('address_building_name', optional($basic_setting)->address_building_name) }}">
          @error('address_building_name')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">電話番号 <span class="badge badge-danger">必須</span></label>
          <input placeholder="例）0312345678" id="phone_number"
                 class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                 name="phone_number"
                 type="tel"
                 value="{{ old('phone_number', optional($basic_setting)->phone_number) }}">
          @error('phone_number')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="">その他書くことがあれば</label>
          <textarea
              class="form-control form-control-lg"
              rows="5"
              name="other_memo">{{old('other_memo', optional($basic_setting)->other_memo)}}</textarea>
          @error('other_memo')
          <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
          @enderror
        </div>
        <!-- 送信ボタン -->
        {{ Form::submit('保存する', ['class' => 'btn btn-primary btn-lg']) }}
        {{ form::close() }}
      </div>
    </div>
  </div>
@endsection
