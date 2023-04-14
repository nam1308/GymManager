{{getenv('APP_TITLE_JA')}}パスワードリセット<br>
<br>
<p>■パスワードリセット画面</p>
<a href="{{url(route('admin.forgot-password.token', $data['token']))}}?email={{$data['email']}}">{{url(route('admin.forgot-password.token', $data['token']))}}?email={{$data['email']}}</a><br>
<br>
@include('emails.common_written')
