@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    @if($channel_join)
      {{ Breadcrumbs::render('trainer.show', $trainer) }}
      <div class="card" style="margin-bottom: 20px;">
        <div class="card-body text-center">
          <p><img class="rounded-circle" src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200"></p>
          <p class="font-weight-bold">{{$trainer->name}}</p>
          <p>{!! nl2br($trainer->self_introduction) !!}</p>
        </div>
      </div>
      <a href="{{route('channel.trainer.reservation.show', [$trainer->vendor_id, $trainer->id])}}" class="btn btn-primary btn-lg btn-block">予約する</a>
      <a href="{{route('channel.trainer', $trainer->vendor_id)}}" class="btn btn-primary btn-block btn-lg">別のトレーナーで予約する</a>
    @else
      <div class="alert alert-warning" role="alert">
        トレーナーが見つかりません
      </div>
    @endif
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
