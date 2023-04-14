@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.user.show', $user) }}
    <div class="row">
      <div class="col-3">
        <table class="table table-bordered table-striped">
          <thead>
          <th colspan="2">会員データー</th>
          </thead>
          <tbody>
          <tr>
            <th style="width:25%">会員ID</th>
            <td>{{ $user->id }}</td>
          </tr>
          <tr>
            <th>名前</th>
            <td>{{ $user->display_name}}</td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td>{{ $user->email }}</a></td>
          </tr>
          <tr>
            <th>電話番号</th>
            <td>{{ $user->phone_number }}</td>
          </tr>
          <tr>
            <th>登録日</th>
            <td>{{ $user->created_at }}</td>
          </tr>
          <tr>
            <th>更新日</th>
            <td>{{ $user->updated_at }}</td>
          </tr>
          <tr>
            <th>削除日</th>
            <td>{{ $user->deleted_at }}</td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><a href="{{ route('super-admin.user.edit', $user->id) }}">編集</a></th>
          </tr>
          </tbody>
        </table>
        <br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#exampleModalCenter">
          {{$user->display_name}}さんを停止する
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">停止確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <strong>{{ $user->display_name}}さんを停止しますか？</strong>
                <br>
                ※復活はいつでもできます。
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル</button>
                {!! Form::open(['route' => ['super-admin.user.destroy', $user->id], 'method' => 'delete']) !!}
                {!! Form::submit('停止する', ['class' => 'btn btn-danger btn-lg']) !!}
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-9">
        <h1>予約一覧</h1>
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
          @foreach($reservations as $reservation)
            <tr>
              <td>{{$reservation->reservation_start}}</td>
              <td>{{$reservation->id}}</td>
              <td><a href="{{route('super-admin.trainer.show', $reservation->admin_id)}}">{{$reservation->admin->name}}</a></td>
              <td>{{$reservation->shop->name}}</td>
              <td>{{$reservation->course->name}}（{{$reservation->course->view_course_time}}）</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
