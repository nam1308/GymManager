<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="{{ mix('js/app.js') }}" defer></script>
  @stack('javascript-head')
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  @stack('css')
</head>
<body>
<div id="app">
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      {{--      <a class="navbar-brand" href="{{ url('/home') }}">--}}
      {{--        {{ config('app.name', 'Laravel') }}--}}
      {{--      </a>--}}
      <a class="navbar-brand" href="/home">
        {{ config('app.name', 'Laravel') }}
      </a>
      <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto"></ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login.line') }}">{{ __('ログイン') }}</a>
            </li>
            {{--            @if (Route::has('register'))--}}
            {{--              <li class="nav-item">--}}
            {{--                <a class="nav-link" href="{{ route('register') }}">{{ __('登録') }}</a>--}}
            {{--              </li>--}}
            {{--            @endif--}}
          @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown"
                 class="nav-link dropdown-toggle"
                 href="#"
                 role="button"
                 data-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="false"
                 v-pre> メニュー<span class="caret"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('reservation') }}"> {{ __('予約確認') }} </a>
                <a class="dropdown-item" href="{{ route('channel') }}"> {{ __('トレーナから予約する') }} </a>
                <a class="dropdown-item" href="{{ route('user.edit') }}"> {{ __('お客様情報') }} </a>
                {{--                <a class="dropdown-item" href="{{ route('help') }}"> {{ __('ヘルプ') }} </a>--}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> {{ __('ログアウト') }} </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  <main class="py-4">
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
  </main>
</div>
@stack('javascript-footer')
</body>
</html>
