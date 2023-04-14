@extends('layouts.welcome')
@push('css')
@endpush
@push('javascript-head')
  <script type="module">
  </script>
@endpush
@section('content')
  <div class="container">
    <h4 style="font-weight: bold">アカウントが作成されました。</h4>
    <p>{{getenv('APP_TITLE_JA')}}運営局からログイン用のメールをお送りしました。</p>
    <div class="text-center">
      <a href="{{route('admin.login')}}" class="btn btn-lg btn-primary">ログイン画面に移動する</a>
      <a href="/" class="btn btn-lg btn-primary">トップに移動する</a>
    </div>
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
