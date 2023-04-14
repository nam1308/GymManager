@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.basic-setting') }}
    <table class="table table-bordered">
      <tbody>
      <tr>
        <th scope="col">会社名</th>
      </tr>
      <tr>
        <td scope="row">{{$basic_setting->company_name}}</td>
      </tr>
      <tr>
        <th scope="col">住所</th>
      </tr>
      <tr>
        <td scope="row">〒{{$basic_setting->postal_code}}{{$basic_setting->view_address}}</td>
      </tr>
      <tr>
        <th scope="col">電話番号</th>
      </tr>
      <tr>
        <td scope="row">{{$basic_setting->phone_number}}</td>
      </tr>
      <tr>
        <th scope="col">営業時間</th>
      </tr>
      <tr>
        <td scope="row">
          @if($business_hour)
            {{$business_hour->start}} 〜 {{$business_hour->end}}
          @endif
        </td>
      </tr>
      <tr>
        <th scope="col">その他のメモ</th>
      </tr>
      <tr>
        <td scope="row">{!! nl2br($basic_setting->other_memo) !!}</td>
      </tr>
      </tbody>
    </table>
    <a href="{{route('admin.basic-setting.edit')}}" class="btn btn-primary btn-lg">編集</a>
  </div>
@endsection
