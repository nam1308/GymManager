@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.home') }}
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">売上ID</th>
        <th scope="col">名前</th>
        <th scope="col">ストライプID</th>
        <th scope="col">ステータス</th>
        <th scope="col">数量</th>
        <th scope="col">解約日</th>
        <th scope="col">登録日</th>
        <th scope="col">更新日</th>
      </tr>
      </thead>
      <tbody>
      @foreach($subscriptions as $sub)
        <tr>
          <td>{{$sub->id}}</td>
          <td>{{$sub->admin}}</td>
          <td>{{$sub->stripe_id}}</td>
          <td>{{$sub->stripe_status}}</td>
          <td>{{$sub->quantity}}</td>
          <td>{{$sub->ends_at}}</td>
          <td>{{$sub->created_at}}</td>
          <td>{{$sub->updated_at}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection
