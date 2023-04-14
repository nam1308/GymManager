@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')

@endpush
@section('content')
  <div class="container">
    <h1>ライン専用パーソナルジム予約システム</h1>
    <div class="card">
      <div class="card-body">
        ライン１つで予約ができる。
      </div>
    </div>
    <br>
    <div class="text-center">
      <div style="margin-bottom: 20px;">
        <a class="btn-lg btn btn-primary" href="{{route('apply')}}">無料登録はこちら</a>
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush

