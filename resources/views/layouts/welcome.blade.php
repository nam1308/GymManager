<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="{{ asset('js/app.js') }}" defer></script>
  @stack('javascript-head')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('css')
</head>
<body>
<div id="app">
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
