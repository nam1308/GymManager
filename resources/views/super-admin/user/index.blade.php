@extends('layouts.super-admin')

@push('javascript-head')
  <script type="module">
  </script>
@endpush

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.user') }}
    <div class="row">
      <div class="col-sm-3">
        <div class="card">
          <div class="card-header">
            検索
          </div>
          <div class="card-body">
            <form method="GET" action="{{ route('super-admin.user') }}">
              <form>
                <div class="form-group">
                  <label>ライン名</label>
                  <input
                      type="text"
                      value="{{Request::get("display_name") }}"
                      name="display_name"
                      class="form-control form-control-lg">
                </div>
                <div class="form-group">
                  <label>名前</label>
                  <input
                      type="text"
                      name="name"
                      value="{{Request::get("name") }}"
                      class="form-control form-control-lg">
                </div>
                <div class="form-group">
                  <label>メールアドレス</label>
                  <input
                      type="text"
                      name="email"
                      value="{{Request::get("email") }}"
                      class="form-control form-control-lg">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">検索</button>
                <hr>
                <a type="button" class="btn btn-light btn-lg btn-block" href="{{route('super-admin.user')}}" id="clearButton">クリア</a>
                <hr>
              </form>
            </form>
            <form method="post" action="{{route('super-admin.user.export')}}{{get_query(Request::url(),Request::fullUrl())}}">
              @csrf
              <button type="submit" class="btn btn-primary btn-lg btn-block">CSV</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        {{ $channel_joins->render() }}
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th>ライン名</th>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>電話番号</th>
            <th>登録チャンネル名</th>
            <th>登録日</th>
            <th>更新日</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($channel_joins as $join)
            <tr>
              <td>
                <img src="{{optional($join->user)->picture_url}}" class="rounded-circle" alt="profile" width="30">
                <a href="{{route('super-admin.user.show', $join->user_id)}}">{{ $join->user->display_name }}</a>
              </td>
              <td>{{ $join->user->name }}</td>
              <td>{{ $join->user->email }}</td>
              <td>{{ $join->user->phone_number}}</td>
              <td>{{ $join->lineMessage->channel_name}}</td>
              <td>{{ $join->user->created_at }}</td>
              <td>{{ $join->user->updated_at }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    {{ $channel_joins->render() }}
  </div>
@endsection
