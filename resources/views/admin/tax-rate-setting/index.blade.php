@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('admin.tax-rate-setting') }}
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="col">
          <div class="card">
            <div class="card-header"> {{ __('税率設定') }} </div>
            <div class="card-body">
              {{ Form::open(['url' => route('admin.tax-rate-setting.update')]) }}
              @method('PUT')
              <div class="form-group">
                <div class="input-group mb-3">
                  <input
                      autofocus
                      type="text"
                      class="form-control form-control-lg @error('tax') is-invalid @enderror"
                      name="tax"
                      placeholder="1.1" value="{{old('tax', optional($tax)->tax)}}">
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">%</span>
                  </div>
                </div>
                @error('tax')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
              </div>
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
