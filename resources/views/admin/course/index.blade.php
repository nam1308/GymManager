@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.course') }}
    <div class="row">
      <div class="col-12">
        <div style="margin-bottom: 20px">
          <a href="{{route('admin.course.create')}}" class="btn btn-lg btn-success">メニュー新規登録</a>
        </div>
        @if(count($courses) > 0)
          @foreach($courses as $course)
            <table class="table table-striped table-bordered">
              <tbody>
              <tr>
                <td>メニュー名：<a href="{{route('admin.course.edit', $course->id)}}">{{$course->name}}</a></td>
              </tr>
              <tr>
                <td>時間：{{$course->view_course_time}}</td>
              </tr>
              <tr>
                <td>金額：{{$course->view_price}}</td>
              </tr>
              <tr>
                <td>内容：{!! nl2br($course->contents) !!}</td>
              </tr>
              <tr>
                <td>
                  <form method="POST" action="{{route('admin.course.delete', $course->id)}}" accept-charset="UTF-8">
                    @csrf
                    @method('DELETE')
                    <input
                        class="btn btn-danger btn-sm"
                        type="submit"
                        value="削除する"
                        onclick="return confirm('メニュー名「{{$course->name}}」削除します。よろしいですか？')">
                  </form>
                </td>
              </tr>
              </tbody>
            </table>
          @endforeach
        @else
          <div class="alert alert-warning" role="alert">
            メニューは現在登録されておりません。<a href="{{route('admin.course.create')}}">メニュー登録する</a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
