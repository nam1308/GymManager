@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.home') }}
    <div class="row">
      <div class="col-4">
        <table class="table table-bordered">
          <tbody>
          <tr>
            <td>お申込み件数 <a href="{{route('super-admin.apply')}}">{{$apply_count}}</a>件</td>
          </tr>
          <tr>
            <td>LINE申請 <a href="{{route('super-admin.line-apply')}}">{{$line_message_count}}</a>件</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-4">
        <div style="width:1000px">
          <canvas id="canvas" height="280" width="600px"></canvas>
        </div>
      </div>
    </div>
  </div>
@endsection
