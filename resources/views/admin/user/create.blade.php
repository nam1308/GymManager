@extends('layouts.admin')

@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('admin.user.create') }}
    <div class="container">
      <div class="col">
        <div class="card">
          <div class="card-header">{{ __('会員登録') }}</div>
          <div class="card-body">
            {{ Form::open(['url' => route('admin.user.store'), 'class' => 'h-adr']) }}
            <span class="p-country-name" style="display:none;">Japan</span>
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">名前 <span class="badge badge-danger">必須</span></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <input placeholder="例）山田太郎"
                             class="form-control form-control-lg @error('name') is-invalid @enderror"
                             autofocus
                             name="name"
                             type="text"
                             value="{{ old('name') }}">
                      @error('name')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">名前（カタカナ） <span class="badge badge-danger">必須</span></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <input placeholder="例）ヤマダタロウ"
                             class="form-control form-control-lg @error('name_kana') is-invalid @enderror"
                             autofocus
                             name="name_kana"
                             type="text"
                             value="{{ old('name_kana') }}">
                      @error('name_kana')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label for="staticEmail" class="col-sm-2 col-form-label">電話番号 <span class="badge badge-danger">必須</span></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <input placeholder="例）08011112222" id="phone_number"
                             class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                             name="phone_number"
                             type="tel"
                             value="{{ old('phone_number') }}">
                      @error('phone_number')
                      <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <label class="col-sm-2 col-form-label">メールアドレス </label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-8">
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
            </div>
            {{ Form::submit('会員登録', ['class' => 'btn btn-success btn-lg btn-block']) }}
            {{ form::close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
