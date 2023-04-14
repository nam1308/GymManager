@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('channel') }}
    <h1>チャンネル一覧</h1>
    @foreach($channels as $channel)
      <div class="card" style="margin-bottom: 30px">
        {{--      <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap"><title>Placeholder</title>--}}
        {{--        <rect fill="#868e96" width="100%" height="100%"/>--}}
        {{--        <text fill="#dee2e6" dy=".3em" x="50%" y="50%">Image cap</text>--}}
        {{--      </svg>--}}
        <div class="card-body">
          <h5 class="card-title">{{$channel->basicSetting->company_name}}</h5>
          <p class="card-text">〒{{$channel->basicSetting->postal_code}} {{$channel->basicSetting->view_address}}</p>
          <p class="card-text">☎︎{{$channel->basicSetting->phone_number}}</p>
        </div>
        <ul class="list-group list-group-flush">
          @foreach($channel->trainers as $trainer)
            @if($trainer->trainer_role == config('const.TRAINER_ROLE.TRAINER.STATUS'))
              <li class="list-group-item">
                <img class="rounded-circle" src="{{$trainer->profileImage->getPhotoUrl()}}" alt="" width="100"> <a href="{{route('channel.trainer.show',[$trainer->vendor_id, $trainer->id])}}">{{$trainer->name}}</a>
              </li>
            @endif
          @endforeach
        </ul>
      </div>
    @endforeach
  </div>
@endsection
@push('javascript-footer')
@endpush
