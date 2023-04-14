@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('home') }}
    @if($admin->role == config('const.ADMIN_ROLE.ADMIN.STATUS'))
      @if(!$trainer_count)
        <div class="alert alert-warning" role="alert">
          ・【任意】トレーナーを招待してください。<a href="{{route('admin.invitation.create')}}">トレーナー招待</a><br>
          ・トレーナ権限を確認する <a href="{{route('admin.trainer')}}">確認する</a>
        </div>
      @endif
      @if(!$admin->self_introduction)
        <div class="alert alert-warning" role="alert">
          ・【必須】プロフィールを完成させてください。<a href="{{route('admin.profile.edit')}}">プロフィール編集</a>
        </div>
      @endif
      @if(!$shop_count)
        <div class="alert alert-warning" role="alert">
          <strong>
            ・<span class="text-danger">【必須】</span>① 店舗登録<br>
            ラインチャネルを利用するには店舗登録をしてください。<br>
            <br>
            <a href="{{route('admin.shop.create')}}"> 店舗登録する</a>
          </strong>
        </div>
      @endif
      @if(!$course_count)
        <div class="alert alert-warning" role="alert">
          <strong>
            ・<span class="text-danger">【必須】</span>② メニュー登録<br>
            ラインチャネルを利用するにはメニュー登録をしてください。<br>
            <br>
            <a href="{{route('admin.course.create')}}">メニュー登録する</a>
          </strong>
        </div>
      @endif
      @if(!$line_message)
        <div class="alert alert-warning" role="alert">
          <strong>
            ・<span class="text-danger">【必須】</span>③ ラインチャンネル申請<br>
            <span class="text-danger">ラインチャネル申請は①と②の登録が完了してから登録してください。</span>
            <br>
            <br>
            <a href="{{route('admin.line-apply.create')}}">チャンネル申請登録する</a><br>
          </strong>
        </div>
      @endif
      @if(!$trainer_count)
        <div class="alert alert-warning" role="alert">
          <strong>
            ・<span class="text-danger">【必須】</span>④ トレーナー権限を変更する<br>
            <span class="text-danger">トレーナー管理からトレーナーを最低1名設定してください（オーナー兼任の場合も含む）</span>
            <br>
            <br>
            <a href="{{route('admin.trainer')}}">トレーナー権限を変更する</a><br>
          </strong>
        </div>
      @endif
    @endif
    <div style="margin-bottom: 100px">
      <h3>仮予約 <a href="{{route('admin.reservation.individual')}}">{{$reservation_count}}</a> 件</h3>
      <h3>トレーナー数 <a href="{{route('admin.trainer')}}">{{$trainer_count}}</a> 件</h3>
      <h3>営業時間：{{$start}}~{{$end}} 変更は<a href="{{route('admin.business-hours')}}">こちら</a></h3>
    </div>
  </div>
@endsection
