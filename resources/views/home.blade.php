@extends('layouts.app')
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('home') }}
    <div class="row" style="margin-bottom: 10px;">
      @if($channel_join_count > 0)
        <div class="col">
          <a href="{{route('channel')}}" class="btn btn-lg btn-block btn-primary">トレーナから予約する</a>
        </div>
      @endif
    </div>
    <hr>
    <div style="margin-bottom: 15px;">
      <a href="{{route('reservation')}}" class="btn btn-lg btn-block btn-primary">予約確認</a>
    </div>
    <div style="margin-bottom: 15px;">
      <a href="{{route('user.edit')}}" class="btn btn-lg btn-block btn-primary">お客様情報</a>
    </div>
    {{--    <div style="margin-bottom: 30px;">--}}
    {{--      <a href="{{route('help')}}" class="btn btn-lg btn-block btn-primary">ヘルプ</a>--}}
    {{--    </div>--}}
  </div>
@endsection
