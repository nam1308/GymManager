@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')

@endpush
@section('content')
  <div class="container">
    <div style="margin-bottom: 20px;">
      <h3>パーソナルジム予約システム</h3>
    </div>
    <div class="alert alert-info" role="alert">
      予約するには必ずラインログインしてください。
    </div>
    <div class="text-center">
      <div class="card">
        <div class="card-header">
          マイページにログイン
        </div>
        <div class="card-body">
          <p class="card-text">
            ログイン時の認証画面にて許可をいただいた場合にのみ、あなたのLINEアカウントに登録されているメールアドレスを取得します。
            取得したメールアドレスは以下の目的以外では使用しません。また法令に定めらた場合を除き、第三者への提供はいたしません。
          </p>
          <ul>
            <li>ユーザIDとしてアカウントの管理に利用</li>
            <li>パスワード再発行の本人確認に利用</li>
          </ul>
          <a href="{{route('login.line.social-login')}}">
            <img src="{{asset('storage/images/btn_login_press.png')}}" alt="line login">
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush

