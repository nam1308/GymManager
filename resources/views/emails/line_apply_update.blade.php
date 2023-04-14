<p>トレーナがLINEチャンネル情報を更新しました。</p>
<p>チャネル更新後は必ずライン側も変更をしてください。</p>
<br>
トレーナー名<br>
{{$data['name']}}様<br>
<br>
ベンダーID<br>
{{$data['vendor_id']}}<br>
<br>
<br>
<p>管理画面から対応してください</p>
<a href="{{$data['url']}}">{{$data['url']}}</a>
<br>
@include('emails.common_written')
