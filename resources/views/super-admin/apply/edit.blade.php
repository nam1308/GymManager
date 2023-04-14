@extends('layouts.super-admin')

@section('content')
  <script type="module">
		window.addEventListener("DOMContentLoaded", function () {
			document.getElementById("phone_number").addEventListener("change", function () {
				const p = getFormatPhone(this.value);
				if (p) {
					this.value = p;
				}
			}, false);
			document.getElementById("cellphone_number").addEventListener("change", function () {
				const p = getFormatPhone(this.value);
				if (p) {
					this.value = p;
				}
			}, false);
		}, false);
  </script>
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.apply.edit', $apply) }}
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="col">
          <div class="card">
            <div class="card-header">{{ __('会員登録') }}</div>
            <div class="card-body">
              {{ Form::open(['url' => route('super-admin.apply.update', $apply->id), 'class' => 'h-adr']) }}
              @method('put')
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="name">名前 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）山田"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           autofocus name="name" type="text"
                           value="{{ old('name', optional($apply)->name) }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
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
                           value="{{ old('phone_number', $apply->phone_number) }}">
                    @error('phone_number')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="name">郵便番号 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）111-2222" id="postal_code"
                           class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                           size="8" maxlength="8" name="postal_code" type="text"
                           value="{{ old('postal_code', $apply->postal_code) }}">
                    @error('postal_code')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
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
                        <option value="{{ $key }}" @if ($key == old('prefecture_id', $apply->prefecture_id)) selected @endif>{{ $val }}</option>
                      @endforeach
                    </select>
                    @error('prefecture_id')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
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
                           value="{{ old('municipality', $apply->municipality) }}">
                    @error('municipality')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
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
                           value="{{ old('address_building_name', $apply->address_building_name) }}">
                    @error('address_building_name')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">電話電話 <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）03-1234-5678" id="phone_number"
                           class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                           name="phone_number" type="tel"
                           value="{{ old('phone_number', optional($apply)->phone_number) }}">
                    @error('phone_number')
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
                    <label for="name">メールアドレス <span class="badge badge-danger">必須</span></label>
                    <input placeholder="例）example@example.com"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           name="email" type="text" value="{{ old('email', optional($apply)->email) }}">
                    @error('email')
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
                    <label for="name">会社名 <span class="badge badge-danger">必須</span></label>
                    <input placeholder=""
                           class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                           name="company_name"
                           type="text"
                           value="{{ old('company_name', $apply->company_name) }}">
                    @error('password')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
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
