@extends('layouts.admin')

@section('content')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <div class="container">
    @if($shop)
      {{ Breadcrumbs::render('admin.shop.edit', $shop) }}
      <div class="card">
        <div class="card-header">{{ __('店舗編集') }}</div>
        <div class="card-body">
          <span class="p-country-name" style="display:none;">Japan</span>
          {{ Form::open(['url' => route('admin.shop.update', $shop->id), 'class' => 'h-adr']) }}
          @method('PUT')
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">店舗名 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input
                        placeholder="例）新宿店"
                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                        autofocus
                        name="name"
                        type="text"
                        value="{{ old('name', $shop->name) }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">URL</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input
                        placeholder="例）https://example.com"
                        class="form-control form-control-lg @error('url') is-invalid @enderror"
                        name="url"
                        type="text"
                        value="{{ old('url', $shop->url) }}">
                    @error('url')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">電話番号</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input
                        placeholder="例）0311112222"
                        class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                        name="phone_number"
                        type="tel"
                        value="{{ old('phone_number', $shop->phone_number) }}">
                    @error('phone_number')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">郵便番号 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <input
                        placeholder="例）1112222" id="postal_code"
                        class="form-control form-control-lg p-postal-code @error('postal_code') is-invalid @enderror"
                        size="8"
                        maxlength="7"
                        name="postal_code"
                        type="tel"
                        value="{{ old('postal_code', $shop->postal_code) }}">
                    @error('postal_code')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">都道府県 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <select
                        class="form-control form-control-lg p-region-id @error('prefecture_id') is-invalid @enderror"
                        name="prefecture_id">
                      <option value="">選択</option>
                      @foreach (prefectures() as $key => $val)
                        <option value="{{ $key }}" @if ($key == old('prefecture_id', $shop->prefecture_id)) selected @endif>{{ $val }}</option>
                      @endforeach
                    </select>
                    @error('prefecture_id')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">市区町村 <span class="badge badge-danger">必須</span></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input
                        placeholder="例）千代田区" id="municipality"
                        class="form-control form-control-lg p-locality p-street-address p-extended-address @error('municipality') is-invalid @enderror"
                        name="municipality" value="{{ old('municipality', $shop->municipality) }}">
                    @error('municipality')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">番地・ビル名</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <input
                        placeholder="" id="address_building_name"
                        class="form-control form-control-lg @error('address_building_name') is-invalid @enderror"
                        name="address_building_name"
                        value="{{ old('address_building_name', $shop->address_building_name) }}">
                    @error('address_building_name')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <label class="col-sm-2 col-form-label">内容</label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                      <textarea
                          rows="5"
                          class="form-control"
                          placeholder="休みや営業時間など"
                          name="contents">{{old('contents', $shop->contents)}}</textarea>
                    @error('contents')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
            </div>
          </div>
          {{ Form::submit('登録', ['class' => 'btn btn-success btn-lg btn-block']) }}
          {{ form::close() }}
        </div>
      </div>
  </div>
  @else
    <div class="alert alert-warning" role="alert">
      店舗が見つかりません
    </div>
  @endif
@endsection
