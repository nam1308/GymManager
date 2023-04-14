@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid" style="margin-bottom: 100px;">
    @if($shop)
      <table class="table table-bordered">
        <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">管理者</th>
          <th scope="col">名前</th>
          <th scope="col">住所</th>
          <th scope="col">電話番号</th>
          <th scope="col">URL</th>
          <th scope="col">内容</th>
          <th scope="col">登録日</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>{{$shop->id}}</td>
          <td><a href="{{route('super-admin.trainer.show', $shop->admin->id)}}">{{$shop->admin->name}}</a></td>
          <td>{{$shop->name}}</td>
          <td>〒{{$shop->postal_code}} {{$shop->view_address}}</td>
          <td>{{$shop->phone_number}}</td>
          <td>{{$shop->url}}</td>
          <td>{{$shop->contents}}</td>
          <td>{{$shop->created_at}}</td>
        </tr>
        </tbody>
      </table>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
  </div>
@endsection
