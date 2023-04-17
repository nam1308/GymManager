@extends('layouts.admin')

@section('content')
  <div class="container">
    @if($trainer)
      {{ Breadcrumbs::render('admin.trainer.show', $trainer) }}
      <table class="table table-bordered table-striped">
        <thead>
        <th colspan="2">トレーナー情報</th>
        </thead>
        <tbody>
        <tr>
          <th style="width: 200px;">プロフィール画像</th>
          <td><img class="rounded-circle" src="{{ $trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200"></td>
        </tr>
        <tr>
          <th>名前</th>
          <td>{{ $trainer->name }}</td>
        </tr>
        <tr>
          <th>メールアドレス</th>
          <td>{{ $trainer->email }}</td>
        </tr>
        <tr>
          <th>システム権限</th>
          <td>{{ $trainer->view_role }}</td>
        </tr>
        <tr>
          <th>トレーナー権限</th>
          <td>{{ $trainer->view_trainer_role }}</td>
        </tr>
        <tr>
          <th>店舗URL</th>
          <td>
            <a target="_blank" href="{{ route('channel.show', $trainer->vendor_id)}}">{{ route('channel.show', $trainer->vendor_id)}}</a>
          </td>
        </tr>
        <tr>
          <th>プロフィールURL</th>
          <td>
            <a target="_blank" href="{{ route('channel.trainer.reservation.show', [$trainer->vendor_id, $trainer->id])}}">
              {{ route('channel.trainer.reservation.show', [$trainer->vendor_id, $trainer->id])}}
            </a>
          </td>
        </tr>
        <tr>
          <th>自己紹介</th>
          <td>{!! $trainer->self_introduction !!}</td>
        </tr>
        <tr>
          <th>登録日</th>
          <td>{{ $trainer->view_created_at }}</td>
        </tr>
        <tr>
          <th>更新日</th>
          <td>{{ $trainer->view_updated_at }}</td>
        </tr>
        <tr>
          <th>停止日</th>
          <td>{{ $trainer->view_deleted_at }}</td>
        </tr>
        </tbody>
      </table>
      <br>
      {{--      <!-- Button trigger modal -->--}}
      {{--      @if($admin->role == 10)--}}
      {{--        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#exampleModalCenter" @if($trainer->role == 10)disabled @endif>--}}
      {{--          {{$trainer->name}}さんを停止する--}}
      {{--        </button>--}}
      {{--      @endif--}}
    @endif
  </div>
  {{--  <!-- Modal -->--}}
  {{--  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"--}}
  {{--       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">--}}
  {{--    <div class="modal-dialog modal-dialog-centered" role="document">--}}
  {{--      <div class="modal-content">--}}
  {{--        <div class="modal-header">--}}
  {{--          <h5 class="modal-title" id="exampleModalCenterTitle">停止確認</h5>--}}
  {{--          <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
  {{--            <span aria-hidden="true">&times;</span>--}}
  {{--          </button>--}}
  {{--        </div>--}}
  {{--        <div class="modal-body">--}}
  {{--          <strong>{{ $trainer->name }}さんを停止しますか？</strong>--}}
  {{--          <br>--}}
  {{--          ※復活はいつでもできます。--}}
  {{--        </div>--}}
  {{--        <div class="modal-footer">--}}
  {{--          <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル</button>--}}
  {{--          {!! Form::open(['route' => ['admin.trainer.delete', $trainer->id], 'method' => 'delete']) !!}--}}
  {{--          {!! Form::submit('停止する', ['class' => 'btn btn-danger btn-lg']) !!}--}}
  {{--          {!! Form::close() !!}--}}
  {{--        </div>--}}
  {{--      </div>--}}
  {{--    </div>--}}
  {{--    <div class="col-8">--}}
  {{--      予定表--}}
  {{--    </div>--}}
  {{--    @else--}}
  {{--      <div class="alert alert-warning" role="alert">--}}
  {{--        トレーナーが見つかりません--}}
  {{--      </div>--}}
  {{--    @endif--}}
  {{--  </div>--}}
@endsection
