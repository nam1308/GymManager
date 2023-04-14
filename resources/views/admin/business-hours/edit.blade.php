@extends('layouts.admin')

@section('content')
  <script type="module" src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <div class="container">
    {{ Breadcrumbs::render('admin.business-hours.edit') }}
    <div class="card">
      <div class="card-header"> {{ __('営業時間') }} </div>
      <div class="card-body">
        <form method="post" action="{{route('admin.business-hours.update')}}">
          @csrf
          @method('put')
          <div class="row">
            <div class="col">
              <label>開始時間</label>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <select class="form-control form-control-lg times" name="start_time">
                  <option value="">選択してください</option>
                  @foreach(times() as $time)
                    <option value="{{$time}}" @if($time == $start_time) selected @endif>{{$time}}時</option>
                  @endforeach()
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <select id="times" class="form-control form-control-lg times" name="start_minutes">
                  <option value="">選択してください</option>
                  @foreach(minutes() as $time)
                    <option value="{{$time}}" @if($time == $start_minutes) selected @endif>{{$time}}分</option>
                  @endforeach()
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>終了時間</label>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <select class="form-control form-control-lg times" name="end_time">
                  <option value="">選択してください</option>
                  @foreach(times() as $time)
                    <option value="{{$time}}" @if($time == $end_time) selected @endif>{{$time}}時</option>
                  @endforeach()
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <select id="times" class="form-control form-control-lg times" name="end_minutes">
                  <option value="">選択してください</option>
                  @foreach(minutes() as $time)
                    <option value="{{$time}}" @if($time == $end_minutes) selected @endif>{{$time}}分</option>
                  @endforeach()
                </select>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-lg">保存する</button>
        </form>
      </div>
    </div>
  </div>
@endsection
