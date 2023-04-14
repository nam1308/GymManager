@extends('layouts.super-admin')
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.trainer') }}
    <div class="row" style="margin-bottom: 150px;">
      <div class="col-sm-3">
        <div class="card">
          <div class="card-header">検索</div>
          <div class="card-body">
            <form method="GET" action="{{ route('super-admin.trainer') }}">
              <form>
                <div class="form-group">
                  <label>トレーナーID</label>
                  <input
                      type="text"
                      value="{{Request::get("id") }}"
                      name="id"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>ベンダーID</label>
                  <input
                      type="text"
                      value="{{Request::get("vendor_id") }}"
                      name="vendor_id"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>トレーナー名</label>
                  <input
                      type="text"
                      name="name"
                      value="{{Request::get("name") }}"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>店舗</label>
                  <input
                      type="text"
                      name="shop"
                      value="{{Request::get("shop") }}"
                      class="form-control">
                </div>
                <div class="form-group">
                  <label>システム権限</label>
                  <select id="status" class="form-control form-control-lg" name="role">
                    <option value="">選択</option>
                    @foreach (config('const.ADMIN_ROLE') as $status)
                      <option value="{{ $status['STATUS'] }}" @if(Request::get('role') == $status['STATUS']) selected @endif>{{ $status['LABEL'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>トレーナ権限</label>
                  <select id="status" class="form-control form-control-lg" name="trainer_role">
                    <option value="">選択</option>
                    @foreach (config('const.TRAINER_ROLE') as $status)
                      <option value="{{ $status['STATUS'] }}" @if(Request::get('trainer_role') == $status['STATUS']) selected @endif>{{ $status['LABEL'] }}</option>
                    @endforeach
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">検索</button>
                <hr>
                <a type="button" class="btn btn-light btn-lg btn-block" href="{{route('super-admin.trainer')}}" id="clearButton">クリア</a>
                <hr>
              </form>
            </form>
            <form method="post" action="{{route('super-admin.trainer.export')}}{{get_query(Request::url(),Request::fullUrl())}}">
              @csrf
              <button type="submit" class="btn btn-primary btn-lg btn-block">CSV</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        @if(count($trainers) > 0)
          <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
              <th>トレーナーID</th>
              <th>ベンダーID</th>
              <th>トレーナー名</th>
              <th>店舗</th>
              <th>システム権限</th>
              <th>トレーナ権限</th>
              <th>登録日</th>
              <th>更新日</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trainers as $trainer)
              <tr>
                <td>{{$trainer->id}}</td>
                <td>{{$trainer->vendor_id}}</td>
                <td>
                  <img class="rounded-circle" src="{{ $trainer->profileImage->getPhotoUrl()}}" alt="profile" width="30">
                  <a href="{{route('super-admin.trainer.show', $trainer->id)}}">{{$trainer->name}}</a>
                </td>
                <td><a href="{{route('super-admin.basic-setting.show', $trainer->vendor_id)}}">{{$trainer->basicSetting->company_name}}</a></td>
                <td>{{$trainer->view_role}}</td>
                <td>{{$trainer->view_trainer_role}}</td>
                <td>{{$trainer->created_at}}</td>
                <td>{{$trainer->updated_at}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning" role="alert">
            データーはありません。
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
