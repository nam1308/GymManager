@extends('layouts.super-admin')
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.apply') }}
    {{ $applies->render() }}
    @if(count($applies) > 0)
      <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
          <th>ステータス</th>
          <th>申込ID</th>
          <th>名前</th>
          <th>住所</th>
          <th>メールアドレス</th>
          <th>電話番号</th>
          <th>登録日</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($applies as $apply)
          <tr>
            <td>{!! $apply->view_status !!}</td>
            <td>
              <a href="{{ route('super-admin.apply.show', $apply->id) }}">
                {{ $apply->id }}
              </a>
            </td>
            <td> {{ $apply->name}} </td>
            <td>
              〒{{$apply->postal_code}} {{ $apply->view_address}}
            </td>
            <td> {{ $apply->email}} </td>
            <td> {{ $apply->phone_number}} </td>
            <td> {{ $apply->view_created_at}} </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @else
      <div class="alert alert-warning" role="alert">
        データーが見つかりません
      </div>
    @endif
    {{ $applies->render() }}
  </div>
@endsection
