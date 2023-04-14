<p>この度はLINEチャンネル利用申請いただきありがとうございます。</p>
<p>LINEチャンネルが有効になりましたのでご連絡いたします。</p>
<br>
ラインチャンネルQRコード画面<br>
<a href="{{$data['url']}}">{{$data['url']}}</a><br>
こちらのQRコードを読み取ることでチャネル登録ができるようになります。<br>
<br>
管理画面<br>
<a href="{{url(route('admin.login'))}}">{{url(route('admin.login'))}}</a><br>
<br>
@include('emails.common_written')
