@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('trainer') }}
    @if($channel_join)
      <div class="card" style="margin-bottom: 30px;">
        <div class="card-body text-center">
          <h2>{{$basic_setting->company_name}}</h2>
          <p>〒{{$basic_setting->postal_code}} {{$basic_setting->view_address}}</p>
          <p>☎ ︎{{$basic_setting->phone_number}}</p>
        </div>
      </div>
      @foreach($trainers as $trainer)
        <div class="card" style="margin-bottom: 30px;">
          <div class="card-body text-center">
            <div class="text-center">
              <p><img class="rounded-circle" src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200"></p>
              <p class="font-weight-bold">{{$trainer->name}}</p>
              <p>{!! nl2br($trainer->self_introduction) !!}</p>
            </div>
            <a href="{{route('channel.trainer.reservation.show',[$trainer->vendor_id, $trainer->id])}}" class="btn btn-primary btn-lg btn-block">予約する</a>
          </div>
        </div>
      @endforeach
    @else
      <div class="alert alert-warning" role="alert">
        チャンネル登録がされておりません
      </div>
    @endif
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
