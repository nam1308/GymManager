@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.admin-user') }}
    {{ $vendors->render() }}
    <div class="row">
      <div class="col-3">
        <div class="card">
          <div class="card-header">店舗検索</div>
          <div class="card-body">
            {{ Form::open(['url' => route('super-admin.user')]) }}
            <div class="form-group">
              <label for="name">店舗ID</label>
              <input placeholder=""
                     class="form-control form-control-lg"
                     autofocus
                     name="id"
                     type="text"
                     value="{{ old('id') }}">
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="name">名前</label>
                  <input placeholder="例）山田"
                         class="form-control form-control-lg"
                         name="name"
                         type="text"
                         value="{{ old('name') }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="name">メールアドレス</label>
              <input placeholder="例）example@example.com"
                     class="form-control form-control-lg"
                     name="email"
                     type="text"
                     value="">
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="name">電話番号</label>
                  <input placeholder="例）03-1234-5678" id="phone_number"
                         class="form-control form-control-lg"
                         name="phone_number"
                         type="tel"
                         value="{{ old('phone_number') }}">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
              </svg>
              検索する
            </button>
            {{ form::close() }}
          </div>
        </div>
      </div>
      <div class="col-9">
        @if(count($vendors) > 0)
          <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
              <th>会社名</th>
              <th>代表者</th>
              <th>電話番号</th>
              <th>住所</th>
              <th>登録日</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($vendors as $vendor)
              <tr>
                <td><a href="{{ route('super-admin.admin-user.show', $vendor->vendor_id) }}"> {{ $vendor->company_name }} </a></td>
                <td><a href="">{{ $vendor->admin->name }}</a></td>
                <td>{{ $vendor->phone_number }}</a></td>
                <td>〒{{$vendor->postal_code}} {{ $vendor->view_address}}</td>
                <td>{{ $vendor->created_at }}</td>
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
    {{ $vendors->render() }}
  </div>
@endsection
