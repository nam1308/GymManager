@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.apply') }}
    {{ $line_messages->render() }}
    <div class="row">
      <div class="col-3">
        <div class="card">
          <div class="card-header">
            会員検索
          </div>
          <div class="card-body">
            {{ Form::open(['url' => route('super-admin.line-apply'), 'method' => 'get']) }}
            <div class="form-group">
              <label for="name">チャンネル名</label>
              <input placeholder=""
                     class="form-control form-control-lg"
                     autofocus
                     name="channel_name"
                     type="text"
                     value="{{ old('channel_name', $channel_name) }}">
            </div>
            <div class="form-group">
              <label for="name">ベンダーID</label>
              <input placeholder=""
                     class="form-control form-control-lg"
                     autofocus
                     name="vendor_id"
                     type="text"
                     value="{{ old('vendor_id', $vendor_id) }}">
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
              </svg>
              検索する
            </button>
            {{ form::close() }}
            <div style="margin-top: 10px">
              <a href="{{route('super-admin.line-apply')}}" class="btn btn-lg btn-light btn-block">クリア</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-9">
        {{ $line_messages->render() }}
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th>ステータス</th>
            <th>チャンネル名</th>
            <th>ベンダーID</th>
            <th>会社名</th>
            <th>QRコード</th>
            <th>申請日</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($line_messages as $message)
            <tr>
              <td>{!! $message->view_status !!}</td>
              <td>
                <img src="{{$message->getPhotoUrl()}}" alt="" class="rounded-circle" width="30" height="30">
                <a href="{{ route('super-admin.line-apply.show', $message->vendor_id) }}">
                  {{ $message->channel_name}}
                </a>
              </td>
              <td>{{$message->vendor_id}}</td>
              <td><a href="{{route('super-admin.basic-setting.show', $message->vendor_id)}}">{{$message->basicSetting->company_name}}</a></td>
              <td>
                <img alt="" src="{{$message->getQrCodeUrl()}}" width="30" height="30">
              </td>
              <td> {{ $message->created_at}} </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        {{ $line_messages->render() }}
      </div>
    </div>
  </div>
@endsection
