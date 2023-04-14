<p>仮予約が入りました</p>
<p>※現在は「仮予約」状態です。「確定」するか「却下」を管理画面から対応してください。</p>
<br>
<p>仮予約日：{{$reservation_data['reservation_start']}}</p>
<p>メニュー：{{$reservation_data['course_name']}}</p>
<p>店舗名：{{$reservation_data['store_name']}}</p>
<p>予約した人：{{$reservation_data['name']}}</p>
<br>
<p>■管理画面</p>
<a href="{{url(route('admin.reservation.individual'))}}">{{url(route('admin.reservation.individual'))}}</a><br>
<br>
@include('emails.common_written')
