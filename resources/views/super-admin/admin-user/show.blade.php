@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.admin-user.show', $vendor) }}
    <div class="row">
      <div class="col-3">
        <table class="table table-bordered table-striped">
          <thead>
          <th colspan="2">店舗データ</th>
          </thead>
          <tbody>
          <tr>
            <th style="width:25%">店舗ID</th>
            <td>{{ $vendor->vendor_id }}</td>
          </tr>
          <tr>
            <th>会社名</th>
            <td>{{ $vendor->company_name}}</td>
          </tr>
          <tr>
            <th>住所</th>
            <td>〒{{ $vendor->postal_code}}<br>
              {{ $vendor->view_address}}</td>
          </tr>
          <tr>
            <th>電話番号</th>
            <td>{{ $vendor->phone_number }}</td>
          </tr>
          <tr>
            <th>会員数</th>
            <td>{{count($trainers)}}</td>
          </tr>
          <tr>
            <th>登録日</th>
            <td>{{ $vendor->created_at }}</td>
          </tr>
          <tr>
            <th>更新日</th>
            <td>{{ $vendor->updated_at }}</td>
          </tr>
          <tr>
            <th>停止日</th>
            <td>{{ $vendor->deleted_at }}</td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><a href="#">編集</a></th>
          </tr>
          </tbody>
        </table>
        <br>
        <!-- Button trigger modal -->
        {{--                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteModal" @if($admin_user->status == 1) disabled @endif>--}}
        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteModal">
          {{$vendor->company_name}}さんを停止する
        </button>
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">停止確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <strong>
                  {{ $vendor->company_name }}さんを「停止」しますか？<br>
                  停止するとトレーナや利用者もログインや予約ができなくなります。<br>
                  ※復活はいつでもできます。
                </strong>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル
                </button>
                {!! Form::open(['route' => ['super-admin.admin-user.destroy', $vendor->id], 'method' => 'delete']) !!}
                {!! Form::submit('停止', ['class' => 'btn btn-danger btn-lg']) !!}
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-9">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th scope="col">名前</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">権限</th>
            <th scope="col">登録日</th>
            <th scope="col">更新日</th>
          </tr>
          </thead>
          <tbody>
          @foreach($trainers as $trainer)
            <tr>
              <td>{{$trainer->name}}</td>
              <td>{{$trainer->email}}</td>
              <td>{{$trainer->role}}</td>
              <td>{{$trainer->created_at}}</td>
              <td>{{$trainer->updated_at}}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
