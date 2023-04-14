@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('admin.reservation.show', $reservation) }}
    <div class="row">
      <div class="col-12">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>予約日</th>
            <th>予約番号</th>
            <th>トレーナー</th>
            <th>店舗</th>
            <th>メニュー</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{$reservation->reservation_start}}</td>
            <td>{{$reservation->id}}</td>
            <td><a href="{{route('admin.trainer.show', $reservation->admin_id)}}">{{$reservation->admin->name}}</a></td>
            <td>{{$reservation->shop->name}}</td>
            <td>{{$reservation->course->name}}（{{$reservation->course->view_course_time}}）</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
