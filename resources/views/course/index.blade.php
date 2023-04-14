@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    @if($courses)
      @foreach($courses as $course)
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            {{$course->name}}
          </div>
          <div class="card-body">
            {{--                        <h5 class="card-title">Special title treatment</h5>--}}
            <p class="card-text">{{$course->contents}}</p>
            <p class="card-text">{{$course->view_price}}</p>
            <p class="card-text">{{$course->view_course_time}}</p>
            {{--            <a href="{{route('reservation.show')}}" class="btn btn-primary">このメニューを選択</a>--}}
          </div>
        </div>
      @endforeach
    @else

    @endif
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
  </script>
@endpush
