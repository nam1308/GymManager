<p>トレーナー招待メールが届いています。</p>

<p>以下のURLより、登録を完了してください。</p>
{{--<a href="{{url(route('admin.register')'admin-register/'.$data['token'])}}">{{url('admin-register/'.$data['token'])}}</a>--}}
<a href="{{url(route('admin.register.showForm', $data['token']))}}">{{url(route('admin.register.showForm', $data['token']))}}</a>
<br>
@include('emails.common_written')
