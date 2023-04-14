招待したトレーナーが新規登録完了しました。<br>
<br>
■新規トレーナー名<br>
{{$new_admin['name']}}<br>
<br>
<p>管理画面で確認する</p>
<a href="{{url(route('admin.login'))}}">{{url(route('admin.login'))}}</a>
<br>
@include('emails.common_written')
