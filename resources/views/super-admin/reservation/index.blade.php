@extends('layouts.super-admin')
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.reservation') }}
    <div class="row" style="margin-bottom: 150px;">
      <div class="col-sm-3">
        <div class="card">
          <div class="card-header">検索</div>
          <div class="card-body">
            <form method="GET" action="{{ route('super-admin.reservation') }}">
              <form>
                <div class="form-group">
                  <label>予約ステータス</label>
                  <select id="status" class="form-control form-control-lg" name="status">
                    <option value="">選択</option>
                    @foreach (config('const.RESERVATION_STATUS') as $status)
                      @if($status['LABEL'] != 'キャンセル' && $status['LABEL'] != 'お休み')
                        <option value="{{ $status['STATUS'] }}" @if(Request::get('status') == $status['STATUS']) selected @endif>{{ $status['LABEL'] }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>予約ID</label>
                  <input
                      type="text"
                      value="{{Request::get("id") }}"
                      name="id"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>会員名</label>
                  <input
                      type="text"
                      name="user_name"
                      value="{{Request::get("user_name") }}"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>トレーナー名</label>
                  <input
                      type="text"
                      name="trainer_name"
                      value="{{Request::get("trainer_name") }}"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>メニュー</label>
                  <input
                      type="text"
                      name="course"
                      value="{{Request::get("course") }}"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>店舗名</label>
                  <input
                      type="text"
                      name="shop"
                      value="{{Request::get("shop") }}"
                      class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">検索</button>
                <hr>
                <a type="button" class="btn btn-light btn-lg btn-block" href="{{route('super-admin.reservation')}}" id="clearButton">クリア</a>
                <hr>
              </form>
            </form>
            <form method="post" action="{{route('super-admin.reservation.export')}}{{get_query(Request::url(),Request::fullUrl())}}">
              @csrf
              <button type="submit" class="btn btn-primary btn-lg btn-block">CSV</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        @if(count($reservations) > 0)
          {{ $reservations->render() }}
          <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
              <th>ステータス</th>
              <th>予約ID</th>
              <th>予約開始日</th>
              <th>予約完了日</th>
              <th>会員</th>
              <th>トレーナー名</th>
              <th>メニュー</th>
              <th>店舗名</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reservations as $reservation)
              <tr>
                <td> {{ $reservation->view_status}} </td>
                <td> {{ $reservation->id}} </td>
                <td>
                  <a href="{{route('super-admin.reservation.show', $reservation->id)}}"> {{ $reservation->reservation_start}} </a>
                </td>
                <td>
                  <a href="{{route('super-admin.reservation.show', $reservation->id)}}"> {{ $reservation->reservation_end}} </a>
                </td>
                <td>
                  @if($reservation->user_id)
                    <img class="rounded-circle" src="{{optional($reservation->user)->picture_url}}" width="30" alt="">
                    <a href="{{route('super-admin.user.show', $reservation->user_id)}}">{{ $reservation->user->name}}</a>
                  @endif
                </td>
                <td>
                  <img class="rounded-circle" src="{{$reservation->admin->profileImage->getPhotoUrl()}}" alt="profile" width="30" height="30">
                  <a href="{{route('super-admin.trainer.show', [$reservation->vendor_id, $reservation->admin_id])}}"> {{ $reservation->admin->name}} </a>
                </td>
                <td>
                  @if($reservation->course_id)
                    <a href="{{route('course.show', $reservation->course_id)}}"> {{ $reservation->course->name}}（{{$reservation->course->view_course_time}}）</a>
                  @endif
                </td>
                <td><a href=""> {{ $reservation->shop->name}} </a></td>
              </tr>
            @endforeach
            </tbody>
          </table>
          {{ $reservations->render() }}
        @else
          <div class="alert alert-warning" role="alert">
            予約はありません
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
