@extends('layouts.admin')

@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.line-apply.create') }}
    @if($line_message)
      <div class="text-center">
        <h4>ラインチャンネル</h4>
        <div style="margin-bottom: 15px">
          <img src="{{$line_message->getPhotoUrl()}}" alt="" class="rounded-circle" width="150" height="150">
        </div>
      </div>
      <table class="table table-bordered">
        <tbody>
        <tr>
          <th scope="col">ステータス</th>
          <td>{!! $line_message->view_status !!}</td>
        </tr>
        <tr>
          <th scope="col">チャンネル名</th>
          <td>
            {{$line_message->channel_name}}
          </td>
        </tr>
        <tr>
          <th scope="col">チャンネル説明</th>
          <td>{!! nl2br($line_message->channel_description) !!}</td>
        </tr>
        <tr>
          <th scope="col">メールアドレス</th>
          <td>{{$line_message->email}}</td>
        </tr>
        <tr>
          <th scope="col">ラインチャンネルで利用したいURL</th>
          <td><a href="{{$line_message->store_url}}" target="_blank">{{$line_message->store_url}}</a></td>
        </tr>
        <tr>
          <th scope="col">お問い合わせチャンネル</th>
          <td><a href="https://line.me/R/ti/p/{{$line_message->line_url1}}" target="_blank">{{$line_message->line_uri1}}</a></td>
        </tr>
        <tr>
          <th scope="col">プライバシーポリシー</th>
          <td>{{$line_message->privacy_policy_url}}</td>
        </tr>
        <tr>
          <th scope="col">利用規約</th>
          <td>{{$line_message->terms_of_use_url}}</td>
        </tr>
        <tr>
          <th scope="col">登録日</th>
          <td>{{$line_message->created_at}}</td>
        </tr>
        <tr>
          <th scope="col">更新日</th>
          <td>{{$line_message->updated_at}}</td>
        </tr>
        <tr>
          <th scope="col"><a href="{{route('admin.line-apply.edit')}}">変更依頼</a></th>
        </tr>
        </tbody>
      </table>
      {{--        <a href="{{route('admin.line-apply.edit')}}" class="btn btn-warning btn-lg">編集する</a>--}}
    @else
      <div class="alert alert-warning" role="alert">
        データーが見つかりません
      </div>
    @endif
  </div>
@endsection
