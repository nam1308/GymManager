@extends('layouts.admin')

@section('content')
  <divt class="container">
    {{ Breadcrumbs::render('admin.user') }}
    {{ $channel_joins->render() }}
    <div class="row">
      <div class="col-12">
        <table class="table table-striped table-bordered table-hover">
          <tbody>
          @foreach ($channel_joins as $join)
            <tr>
              <td>
                <img src="{{optional($join->user)->picture_url}}" alt="" width="30" class="rounded-circle">
                <a href="{{ route('admin.user.show', $join->user->id)}}">{{ $join->user->display_name }}</a>
              </td>
              <td>{{ $join->user->created_at }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    {{ $channel_joins->render() }}
  </divt>
@endsection
