<p>{{$data['company_name']}}</p>
<p>{{$data['name']}} 様</p>

<p>この度はご登録いただきありがとうございます。</p>
<p>アカウントが有効になりましたので、以下の通りログイン情報をお知らせいたします。</p>
<br>
■管理画面情報<br>
<p>ログインID：{{$data['login_id']}}</p>
<p>パスワード：お申込みの際に設定されたパスワード</p>

<p>以下のURLから管理画面にログインしてください。</p>
<p><a href="{{url('/admin/login')}}">{{url('/admin/login')}}</a></p>
<br>
@include('emails.common_written')
