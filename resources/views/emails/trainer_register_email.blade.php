<p>{{$new_admin['name']}} 様</p>

<p>トレーナーご登録いただきありがとうございます。</p>
<p>アカウントが有効になりましたので、以下の通りログイン情報をお知らせいたします。</p>
<br>
■管理画面情報<br>
<p>ログインID：{{$new_admin['login_id']}}</p>
※ログインIDは大切に保管してください。<br>
<p>パスワード：お申込みの際に設定されたパスワード</p>
<br>
<p>以下のURLからトレーナーとしてログインしてください。</p>
<p>URLはお気に入りに保存してください。</p>
<a href="{{url(route('admin.login'))}}">{{url(route('admin.login'))}}</a>
<br>
@include('emails.common_written')
