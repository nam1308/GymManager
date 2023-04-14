@extends('layouts.app')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('user.edit') }}
    <form method="POST" action="{{ route('user.update') }}">
      @method('PUT')
      @csrf
      <div class="form-group">
        <label>お名前 <span class="badge badge-danger">必須</span></label>
        <input
            type="text"
            class="form-control form-control-lg @error('name') is-invalid @enderror"
            placeholder="山田太郎"
            name="name"
            value="{{old('name', $user->name)}}"
            autofocus>
        @error('name')
        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
        @enderror
      </div>
      <div class="form-group">
        <label>お名前（カタカナ） <span class="badge badge-danger">必須</span></label>
        <input
            type="text"
            class="form-control form-control-lg @error('name_kana') is-invalid @enderror"
            placeholder="ヤマダタロウ"
            value="{{old('name_kana', $user->name_kana)}}"
            name="name_kana">
        @error('name_kana')
        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
        @enderror
      </div>
      <label>生年月日 <span class="badge badge-danger">必須</span></label>
      <div class="row">
        <div class="col-4">
          <div class="form-group">
            <select
                class="form-control form-control-lg @error('birthday_year') is-invalid @enderror"
                style="padding: 0.1rem"
                name="birthday_year">
              <option value="">選択</option>
              @foreach (years() as $val)
                <option value="{{ $val }}" @if ($val == old('birthday_year', optional($user)->birthday_year)) selected @endif>{{ $val }}年</option>
              @endforeach
            </select>
            @error('birthday_year')
            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
            @enderror
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <select
                class="form-control form-control-lg @error('birthday_month') is-invalid @enderror"
                name="birthday_month">
              <option value="">選択</option>
              @foreach (months() as $val)
                <option value="{{ $val }}" @if ($val == old('birthday_month', optional($user)->birthday_month)) selected @endif>{{ $val }}月</option>
              @endforeach
            </select>
            @error('birthday_month')
            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
            @enderror
          </div>
        </div>
        <div class="col-4">
          <div class="form-group">
            <select
                class="form-control form-control-lg @error('birthday_day') is-invalid @enderror"
                name="birthday_day">
              <option value="">選択</option>
              @foreach (days() as $val)
                <option value="{{ $val }}" @if ($val == old('birthday_day', optional($user)->birthday_day)) selected @endif>{{ $val }}日</option>
              @endforeach
            </select>
            @error('birthday_day')
            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
            @enderror
          </div>
        </div>
      </div>
      <label>性別 <span class="badge badge-danger">必須</span></label>
      <div class="col-sm-10">
        @foreach (genders() as $key => $val)
          <div class="form-check form-check-inline">
            <input class="form-check-input form-control-lg @error('gender_id') is-invalid @enderror"
                   type="radio"
                   name="gender_id"
                   id="gender_{{ $key }}"
                   value="{{ $key }}" @if ($key == old('gender_id', optional($user)->gender_id)) checked @endif>
            <label class="form-check-label" for="inlineRadio1">{{ $val }}</label>
          </div>
        @endforeach
        @error('gender_id')
        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
        @enderror
      </div>
      <div class="form-group">
        <label>電話番号 <span class="badge badge-danger">必須</span></label>
        <input
            type="tel"
            class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
            placeholder="09011112222"
            value="{{old('phone_number', $user->phone_number)}}"
            name="phone_number">
        @error('phone_number')
        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary form-control-lg btn-block">保存</button>
    </form>
  </div>
@endsection
