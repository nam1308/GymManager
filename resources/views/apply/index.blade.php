@extends('layouts.welcome')
@push('css')
@endpush
@push('javascript-head')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <script type="module">
  </script>
@endpush
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">{{ __('新規登録') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('apply.store') }}" class="h-adr">
              <span class="p-country-name" style="display:none;">Japan</span>
              @csrf
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('お名前') }}</label>
                <div class="col-md-7">
                  <input placeholder="" id="name"
                         class="form-control form-control-lg p-postal-code @error('name') is-invalid @enderror"
                         name="name"
                         type="text"
                         value="{{ old('name') }}">
                  @error('name')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('会社名・屋号') }}</label>
                <div class="col-md-7">
                  <input placeholder="" id="company_name"
                         class="form-control form-control-lg p-postal-code @error('company_name') is-invalid @enderror"
                         name="company_name"
                         type="text"
                         value="{{ old('company_name') }}">
                  @error('company_name')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('郵便番号') }}</label>
                <div class="col-md-7">
                  <input placeholder="例）1112222" id="postal_code"
                         class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                         size="8"
                         maxlength="8"
                         name="postal_code"
                         type="tel"
                         value="{{ old('postal_code') }}">
                  @error('postal_code')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('都道府県') }}</label>
                <div class="col-md-7">
                  <select
                      class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
                      name="prefecture_id">
                    <option value="">選択</option>
                    @foreach (prefectures() as $key => $val)
                      <option value="{{ $key }}" @if ($key == old('prefecture_id')) selected @endif>{{ $val }}</option>
                    @endforeach
                  </select>
                  @error('prefecture_id')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('市区町村') }}</label>
                <div class="col-md-7">
                  <input placeholder="例）千代田区"
                         id="municipality"
                         class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                         name="municipality"
                         type="text"
                         value="{{ old('municipality') }}">
                  @error('municipality')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('番地・ビル名') }}</label>
                <div class="col-md-7">
                  <input
                      placeholder=""
                      id="address_building_name"
                      class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                      name="address_building_name"
                      type="text"
                      value="{{ old('address_building_name') }}">
                  @error('address_building_name')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('電話番号') }}</label>
                <div class="col-md-7">
                  <input
                      placeholder=""
                      id="phone_number"
                      class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                      name="phone_number"
                      type="tel"
                      value="{{ old('phone_number') }}">
                  @error('phone_number')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('メールアドレス') }}</label>
                <div class="col-md-7">
                  <input
                      id="email"
                      type="email"
                      class="form-control form-control-lg @error('email') is-invalid @enderror"
                      name="email"
                      value="{{ old('email') }}"
                      autocomplete="email">
                  @error('email')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('メールアドレス（確認）') }}</label>
                <div class="col-md-7">
                  <input
                      id="email_confirmation"
                      type="email"
                      class="form-control form-control-lg @error('email_confirmation') is-invalid @enderror"
                      name="email_confirmation"
                      value="{{ old('email_confirmation') }}"
                      autocomplete="email_confirmation">
                  @error('email_confirmation')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('パスワード') }}</label>
                <div class="col-md-7">
                  <input
                      id="password"
                      type="password"
                      class="form-control form-control-lg @error('password') is-invalid @enderror"
                      name="password"
                      autocomplete="current-password">
                  @error('password')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('パスワード（確認）') }}</label>
                <div class="col-md-7">
                  <input
                      id="password_confirmation"
                      type="password"
                      class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                      name="password_confirmation"
                      autocomplete="password_confirmation">
                  @error('password_confirmation')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-7 offset-md-3">
                  <button type="submit" class="btn btn-primary btn-block btn-lg"> {{ __('無料登録する') }} </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
