@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('reservation') }}
    <h2>予約一覧</h2>
    @if(count($reservations) > 0)
      {{ $reservations->render() }}
      <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
          <th>予約日</th>
          <th>トレーナー</th>
          <th>メニュー</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reservations as $reservation)
          <tr>
            <td>
              <a href="{{route('reservation.show', $reservation->id)}}">
                {{ $reservation->start_date}} {{$reservation->start_time}} 〜 {{$reservation->end_time}}
              </a>
            </td>
            <td>
              <img class="rounded-circle" src="{{$reservation->admin->profileImage->getPhotoUrl()}}" alt="profile" width="60" height="60">
              <a href="{{route('channel.trainer.show', [$reservation->vendor_id, $reservation->admin_id])}}"> {{ $reservation->admin->name}} </a>
            </td>
            <td>
              <a href="{{route('course.show', $reservation->course_id)}}"> {{ $reservation->course->name}} </a>
            </td>
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
@endsection
