@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid" style="margin-bottom: 100px;">
    @if($trainer)
      {{ Breadcrumbs::render('super-admin.trainer.show', $trainer) }}
      <div class="row justify-content-md-center">
        <div class="col col-lg-8">
          <table class="table table-striped table-bordered table-hover">
            <tbody>
            <tr>
              <th>プロフィール画像</th>
              <td><img class="rounded-circle" src="{{ $trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200"></td>
            </tr>
            <tr>
              <th>トレーナID</th>
              <td>{{ $trainer->id}}</td>
            </tr>
            <tr>
              <th>店舗ID</th>
              <td>{{ $trainer->vendor_id}}</td>
            </tr>
            <tr>
              <th>ログインID</th>
              <td>{{ $trainer->login_id}}</td>
            </tr>
            <tr>
              <th>名前</th>
              <td>{{ $trainer->name}}</td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td>{{ $trainer->email}}</td>
            </tr>
            <tr>
              <th>システム権限</th>
              <td>{{ $trainer->view_role}}</td>
            </tr>
            <tr>
              <th>トレーナー</th>
              <td>{{ $trainer->view_trainer_role}}</td>
            </tr>
            <tr>
              <th>自己紹介</th>
              <td>{!! $trainer->self_introduction !!}</td>
            </tr>
            <tr>
              <th>登録日</th>
              <td>{{ $trainer->created_at }}</td>
            </tr>
            <tr>
              <th>更新日</th>
              <td>{{ $trainer->updated_at }}</td>
            </tr>
            <tr>
              <td colspan="2">
                <a href="{{route('super-admin.trainer.edit', $trainer->id)}}">編集</a>
              </td>
            </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col">
              <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#userLoginMpdal">
                {{ $trainer->name }}のマイページを確認する
              </button>
            </div>
            <div class="col">
              <form
                  action="{{route('super-admin.trainer.destroy', $trainer->id)}}"
                  method="post">
                @csrf
                <button
                    onclick="return confirm('削除しますか？')"
                    class="btn btn-danger btn-lg btn-block"
                    type="submit">{{$trainer->name}}を削除する
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
  </div>
  <div class="modal fade" id="userLoginMpdal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCeÒÍnterTitle">確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>トレーナー画面にログインする</p>
          <strong>「{{ $trainer->name }}」</strong>さんとしてマイページにログインしますか？
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル</button>
          {!! Form::open(['route' => ['super-admin.trainer.login', $trainer->id], 'method' => 'post' , 'target' => '_blank']) !!}
          {!! Form::submit('ログインする', ['class' => 'btn btn-primary btn-lg']) !!}
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
