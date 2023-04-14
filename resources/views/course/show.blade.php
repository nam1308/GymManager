@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    <div class="text-center">
      <p class="font-weight-bold">{{$course->name}}</p>
      <p>{{$course->contents}}</p>
    </div>
    <a href="" class="btn btn-primary btn-lg btn-block">このメニューを予約する</a>
    <a href="" class="btn btn-primary btn-block btn-lg">別のメニューで予約する</a>
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
