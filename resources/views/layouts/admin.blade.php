<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'ABihc') }}</title>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  @stack('javascript-head')
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('css')
  <style>
      .form-control::placeholder {
          color: #cccccc;
      }
  </style>
</head>

<body style="background: #fff">
<div id="app">
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/admin') }}"> {{ config('app.name', 'Laravel') }} </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">予約管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{route('admin.reservation.individual')}}">個別予約</a>
                {{--                <a class="dropdown-item" href="{{ route('admin.reservation') }}">全体予約</a>--}}
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">トレーナ管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.trainer') }}">トレーナ一覧</a>
                {{-- <a class="dropdown-item" href="{{ route('admin.trainer.create') }}">トレーナー登録</a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.invitation.create') }}">招待</a>
                <a class="dropdown-item" href="{{ route('admin.invitation') }}">招待一覧</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">会員管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.user') }}">会員ー覧</a>
                <a class="dropdown-item" href="{{ route('admin.user.create') }}">会員登録</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">店舗管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.shop') }}">店舗ー覧</a>
                <a class="dropdown-item" href="{{ route('admin.shop.create') }}">店舗登録</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">メニュー管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.course') }}">メニューー覧</a>
                <a class="dropdown-item" href="{{ route('admin.course.create') }}">メニュー登録</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">LINE</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.line-apply.create') }}">LINE利用申請</a>
                <a class="dropdown-item" href="{{ route('admin.line-apply') }}">LINE利用申請状況</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">コンテンツ管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.notification') }}">自動通知管理</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">基本設定</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.basic-setting') }}">会社情報</a>
                <a class="dropdown-item" href="{{ route('admin.business-hours') }}">営業時間</a>
                <a class="dropdown-item" target="_blank" href="{{route('channel.show', \Illuminate\Support\Facades\Auth::user()->vendor_id)}}">QRコード表示</a>
              </div>
            </li>
          @endauth
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->
          @guest
            {{-- <li class="nav-item"> --}}
            {{-- <a class="nav-link"
          href="{{ route('admin.login') }}">{{ __('ログアウト') }}</a> --}}
            {{-- </li> --}}
            {{-- @if (Route::has('admin.register')) --}}
            {{-- <li class="nav-item"> --}}
            {{-- <a class="nav-link"
          href="{{ route('admin.register') }}">{{ __('新規登録') }}</a> --}}
            {{-- </li> --}}
            {{-- @endif --}}
          @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}<span class="caret"></span> </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">プロフィール編集</a>
                <a class="dropdown-item" href="{{ route('admin.password.change') }}">パスワード変更</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('ログアウト') }} </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  <br>
  @if (session('flash_message_success'))
    <div class="col-sm-12">
      <div class="alert alert-success" role="alert">
        <span style="font-weight: bold">{{ session('flash_message_success') }}</span>
      </div>
    </div>
    <br>
  @endif
  @if (session('flash_message_danger'))
    <div class="col-sm-12">
      <div class="alert alert-danger" role="alert">
        <span style="font-weight: bold">{{ session('flash_message_danger') }}</span>
      </div>
    </div>
    <br>
  @endif
  @if (session('flash_message_warning'))
    <div class="col-sm-12">
      <div class="alert alert-warning" role="alert">
        <span style="font-weight: bold">{{ session('flash_message_warning') }}</span>
      </div>
    </div>
    <br>
  @endif
  @yield('content')
</div>
<script src="{{ asset('/js/common.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/format_phone_number.js') }}" charset="utf-8"></script>
@stack('javascript-footer')
</body>

</html>
