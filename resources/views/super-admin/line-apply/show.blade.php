@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid" style="margin-bottom: 100px;">
    <div class="row">
      <div class="col-12">
        <table class="table table-bordered table-striped">
          <thead>
          <th colspan="2">LINEメッセージ情報</th>
          </thead>
          <tbody>
          <tr>
            <th>ステータス</th>
            <td>{!!$line_message_apply->view_status !!}</td>
          </tr>
          <tr>
            <th style="width:25%">コールバックURL</th>
            <td>
              <span style="font-weight: bold;font-size: 20px;">{{url('api/webhook/' . $line_message_apply->callback)}}</span><br>
              <span style="color: red">※こちらのコールバックをライン管理画面に設定をしてください。</span>
            </td>
          </tr>
          <tr>
            <th>チャンネルID</th>
            @if(is_null($line_message_apply->channel_id))
              <td>LINE管理画面の情報を入力してください</td>
            @else
              <td>{{ $line_message_apply->channel_id}}</td>
            @endif
          </tr>
          <tr>
            <th>チャンネルアイコン</th>
            <td>
              <img class="rounded-circle" alt="" src="{{$line_message_apply->getPhotoUrl()}}" width="100" height="100">
            </td>
          </tr>
          <tr>
            <th>QRコード</th>
            <td>
              <img alt="" src="{{$line_message_apply->getQrCodeUrl()}}" width="100" height="100">
            </td>
          </tr>
          <tr>
            <th>チャンネル名</th>
            <td>{{$line_message_apply->channel_name}}</td>
          </tr>
          <tr>
            <th>チャンネル説明</th>
            <td>{!! nl2br($line_message_apply->channel_description) !!}</td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td>{{ $line_message_apply->email }}</td>
          </tr>
          <tr>
            <th>店舗紹介URL</th>
            <td>{{ $line_message_apply->store_url}}</td>
          </tr>
          <tr>
            <th>お問い合わせチャンネル</th>
            <td>{{ $line_message_apply->line_uri1}}</td>
          </tr>
          <tr>
            <th>プライバシーポリシーURL</th>
            <td>{{ $line_message_apply->privacy_policy_url }}</td>
          </tr>
          <tr>
            <th>利用規約URL</th>
            <td>{{ $line_message_apply->terms_of_use_url }}</td>
          </tr>
          <tr>
            <th>ログインチャンネルシークレット</th>
            @if(is_null($line_message_apply->channel_secret))
              <td>LINE管理画面の情報を入力してください</td>
            @else
              <td>{{ $line_message_apply->channel_secret}}</td>
            @endif
          </tr>
          <tr>
            <th>チャンネルアクセストークン</th>
            @if(is_null($line_message_apply->channel_access_token))
              <td>LINE管理画面の情報を入力してください</td>
            @else
              <td>{{ str_limit($line_message_apply->channel_access_token, 80, '...')}}</td>
            @endif
          </tr>
          <tr>
            <th>申請日</th>
            <td>{{ $line_message_apply->created_at }}</td>
          </tr>
          <tr>
            <th>申請日</th>
            <td>{{ $line_message_apply->created_at }}</td>
          </tr>
          </tbody>
        </table>
        <a class="btn btn-lg btn-primary" style="margin-bottom: 20px" href="{{route('super-admin.line-apply.message-edit', $line_message_apply->vendor_id)}}"> 情報を入力 </a>
        <h2>ライン側の設定をしてください。</h2>
        <ul>
          <li>
            「Webhook URL」は設定済みですか？
          </li>
          <li>
            「Webhookの利用」はチェック済みですか？
          </li>
          <li>
            「グループ・複数人チャットへの参加を許可する」は「無効」となっていますか？
          </li>
          <li>
            「応答メッセージ」は「無効」となっていますか？
          </li>
          <li>
            「あいさつメッセージ」は「無効」となっていますか？
          </li>
        </ul>
        <hr>
        <h2>繋ぎ込みテストをする</h2>
        <p>テスト状態にするとステータスは「対応中」となります。トレーナ側にも「対応中」と表示されます。</p>
        <form method="POST" action="{{route('super-admin.line-apply.status-change')}}" accept-charset="UTF-8" class="">
          @csrf
          @method('PUT')
          <input class="btn btn-warning btn-lg" type="submit" value="テスト状態にする">
          <input type="hidden" name="status" value="{{config('const.LINE_STATUS.ENTERED.STATUS')}}">
          <input type="hidden" name="vendor_id" value="{{$line_message_apply->vendor_id}}">
        </form>
        <hr>
        <h2>完了通知を送る</h2>
        <p class="text-danger">必ずテストをして問題ないことを確認してください。ラインチャンネルにメニューが表示されているか？など</p>
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#exampleModalCenter">
          完了通知メールを送る
        </button>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">通知完了確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ラインのチャンネルの設定は完了してますか？<br>
          トレーナー管理者にメールが送信されると同時に「受理」となります。<br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">閉じる</button>
          <form
              method="POST"
              action="{{route('super-admin.line-apply.line-inform',$line_message_apply->vendor_id)}}"
              accept-charset="UTF-8"
              class="">
            @csrf
            @method('PUT')
            <input
                class="btn btn-success btn-lg"
                type="submit"
                value="有効にする">
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
