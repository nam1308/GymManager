@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.course.create') }}
    <div class="card" style="margin-bottom: 150px;">
      <div class="card-header">{{ __('メニュー登録') }}</div>
      {{ Form::open(['url' => route('admin.course.store'), 'class' => 'h-adr']) }}
      <div class="card-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">メニュー名 <span class="badge badge-danger">必須</span></label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <input placeholder="例）メニュー名"
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
          <label class="col-sm-2 col-form-label">メニュー時間 <span class="badge badge-danger">必須</span></label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select
                        id="course_time"
                        class="form-control form-control-lg @error('course_time') is-invalid @enderror"
                        name="course_time">
                      <option value="">時間を選択してください</option>
                      @foreach(get_course_times() as $key => $val)
                        <option value="{{$val['minutes']}}" @if($val['minutes'] == old('course_time')) selected @endif>{{$val['time']}}時間</option>
                      @endforeach
                    </select>
                    @error('course_time')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select
                        id="course_time"
                        class="form-control form-control-lg @error('course_minutes') is-invalid @enderror"
                        name="course_minutes">
                      <option value="">分を選択してください</option>
                      @for($i = 5; $i <= 5 * 12 ; $i += 5)
                        <option value="{{$i}}" @if($i == old('course_minutes')) selected @endif>{{$i}}分</option>
                      @endfor
                    </select>
                    @error('course_minutes')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <label class="col-sm-2 col-form-label">価格 <span class="badge badge-danger">必須</span></label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <input
                        type="tel"
                        class="form-control form-control-lg @error('price') is-invalid @enderror"
                        name="price"
                        placeholder="5000"
                        value="{{old('price')}}"
                        aria-label="price"
                        aria-describedby="price">
                    <div class="input-group-append">
                      <span class="input-group-text" id="price">円</span>
                    </div>
                    @error('price')
                    <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <label class="col-sm-2 col-form-label">内容 <span class="badge badge-danger">必須</span></label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <textarea
                      style="font-size: 16px;"
                      rows="5"
                      class="form-control form-control-lg @error('contents') is-invalid @enderror"
                      placeholder="メニュー内容など"
                      name="contents">{{old('contents')}}</textarea>
                  @error('contents')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            {{ Form::submit('登録', ['class' => 'btn btn-success btn-lg btn-block']) }}
            {{ form::close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
