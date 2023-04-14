@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.basic-setting') }}
    {{ $vendors->render() }}
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
            <td><a href="{{ route('super-admin.basic-setting.show', $vendor->vendor_id) }}"> {{ $vendor->basicSetting->company_name }} </a></td>
            <td><a href="{{ route('super-admin.trainer.show', $vendor->id) }}">{{ $vendor->name }}</a></td>
            <td>{{ $vendor->basicSetting->phone_number }}</a></td>
            <td>〒{{ $vendor->basicSetting->postal_code }} {{ $vendor->basicSetting->view_address }}</td>
            <td>{{ $vendor->basicSetting->created_at }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
    {{ $vendors->render() }}
  </div>
@endsection
