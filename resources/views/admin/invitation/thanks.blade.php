@extends('layouts.admin')

@section('content')
  <div class="container">
    <h3>登録完了</h3>
    <br>
    トレーナ登録ありがとうございます。<br>
    ご登録されたメールアドレスにログインIDをお送りました。<br>
    <br>
    メールアドレスをご確認しログインIDを利用して管理画面からログインしてください。<br>
    <br>
    <a class="btn btn-primary btn-lg btn-block" href="{{route('admin.login')}}">管理画面にログイン</a>
  </div>
@endsection
