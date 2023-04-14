<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'ABihc') }}</title>
  <script src="{{ asset('js/app.js') }}" defer></script>
  @stack('javascript-head')
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('css')
</head>

<body style="background: #fff">
<div id="app">
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/super-admin') }}"> {{ config('app.name', 'Laravel') }} </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse"
              data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
              aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">申込管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{route('super-admin.apply')}}">申込一覧</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">店舗管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.basic-setting') }}">店舗一覧</a>
                <a class="dropdown-item" href="{{ route('super-admin.basic-setting.create') }}">登録</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">予約管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.reservation') }}">予約一覧</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">トレーナ管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.trainer') }}">トレーナ一覧</a>
                <a class="dropdown-item" href="{{ route('super-admin.user.create') }}">トレーナー登録</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">会員管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.user') }}">会員一覧</a>
                {{--                <a class="dropdown-item" href="{{ route('super-admin.user.create') }}">会員登録</a>--}}
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">LINE 管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.line-apply') }}">LINE利用申請一覧</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">リンク管理</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ url('apply') }}" target="_blank">申込画面</a>
                <a class="dropdown-item" href="http://aporze.jp/wp/" target="_blank">LPページ</a>
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
            href="{{ route('super-admin.login') }}">{{ __('ログアウト') }}</a> --}}
            {{-- </li> --}}
            {{-- @if (Route::has('super-admin.register')) --}}
            {{-- <li class="nav-item"> --}}
            {{-- <a class="nav-link"
            href="{{ route('super-admin.register') }}">{{ __('新規登録') }}</a> --}}
            {{-- </li> --}}
            {{-- @endif --}}
          @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span> </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('super-admin.password.change') }}">パスワード変更</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('super-admin.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('ログアウト') }} </a>
                <form id="logout-form" action="{{ route('super-admin.logout') }}" method="POST"
                      style="display: none;">
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
      <div class="flash_message bg-success text-center py-3 my-0">
        <span style="color: #fff; font-weight: bold">{{ session('flash_message_success') }}</span>
      </div>
    </div>
    <br>
  @endif
  @if (session('flash_message_danger'))
    <div class="col-sm-12">
      <div class="flash_message bg-danger text-center py-3 my-0">
        <span style="color: #fff; font-weight: bold">{{ session('flash_message_danger') }}</span>
      </div>
    </div>
    <br>
  @endif
  @if (session('flash_message_warning'))
    <div class="col-sm-12">
      <div class="flash_message bg-warning text-center py-3 my-0">
        <span style="color: #fff; font-weight: bold">{{ session('flash_message_warning') }}</span>
      </div>
    </div>
    <br>
  @endif
  @yield('content')
</div>
<script src="{{ asset('/js/common.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/format_phone_number.js') }}" charset="utf-8"></script>
</body>

</html>
