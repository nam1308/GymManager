@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container">
    @if($line_message)
      <h1>
        <img class="rounded-circle" src="{{$line_message->getPhotoUrl()}}" alt="" width="100">
        {{$line_message->channel_name}}
      </h1>
      <p>〒{{$line_message->basicSetting->postal_code}} {{$line_message->basicSetting->view_address}}</p>
      <p><a href="{{$line_message->store_url}}">{{$line_message->store_url}}</a></p>
      <p><a href="tel:{{$line_message->basicSetting->phone_number}}">{{$line_message->basicSetting->phone_number}}</a></p>
      <div class="text-center">
        <img src="{{$line_message->getQrCodeUrl()}}" alt="">
      </div>
      <p>{!! $line_message->channel_description !!}</p>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
  </div>
@endsection
@push('javascript-footer')
@endpush
