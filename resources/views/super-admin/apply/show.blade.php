@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('super-admin.apply.show', $apply) }}
    <div class="row justify-content-md-center">
      <div class="col col-lg-8">
        <table class="table table-bordered table-striped">
          <thead>
          <th colspan="2">申込データー</th>
          </thead>
          <tbody>
          <tr>
            <th style="width:25%">ステータス</th>
            <td>{!! $apply->view_status !!}</td>
          </tr>
          <tr>
            <th style="width:25%">申込ID</th>
            <td>{{ $apply->id }}</td>
          </tr>
          <tr>
            <th>名前</th>
            <td>{{ $apply->name}}</td>
          </tr>
          <tr>
            <th>住所</th>
            <td>〒{{ $apply->postal_code }}<br>
              {{ $apply->view_address }}</td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td><a href="{{ route('super-admin.apply') }}">{{ $apply->email }}</a></td>
          </tr>
          <tr>
            <th>電話番号</th>
            <td>{{ $apply->phone_number }}</td>
          </tr>
          <tr>
            <th>会社名</th>
            <td>{{ $apply->company_name }}</td>
          </tr>
          <tr>
            <th>登録日</th>
            <td>{{ $apply->view_created_at }}</td>
          </tr>
          <tr>
            <th>更新日</th>
            <td>{{ $apply->view_updated_at }}</td>
          </tr>
          <tr>
            <th>削除日</th>
            <td>{{ $apply->view_deleted_at }}</td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><a href="{{ route('super-admin.apply.edit', $apply->id) }}">編集</a></th>
          </tr>
          </tbody>
        </table>
        <br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#successModal" @if($apply->status == 1) disabled @endif>
          {{$apply->name}}さんを有効にする
        </button>
        <!-- Button trigger modal -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteModal" @if($apply->status == 1) disabled @endif>
          {{$apply->name}}さんを却下する
        </button>
        <!-- Button trigger modal -->
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <strong>{{ $apply->name }}さんを「却下」しますか？</strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル
          </button>
          {!! Form::open(['route' => ['super-admin.apply.destroy', $apply->id], 'method' => 'delete']) !!}
          {!! Form::submit('削除', ['class' => 'btn btn-danger btn-lg']) !!}
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <strong>{{ $apply->name }}さんを「有効」にしますか？</strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル
          </button>
          {!! Form::open(['route' => ['super-admin.apply.update', $apply->id], 'method' => 'post']) !!}
          @method('PUT')
          @csrf
          <input type="hidden" name="status" value="1">
          <input type="hidden" name="role" value="10">
          {!! Form::submit('有効', ['class' => 'btn btn-success btn-lg']) !!}
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
