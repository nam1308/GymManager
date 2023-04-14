<p>{{$data['time_message']}}</p>
<br>
<p>予約日：{{$data['reservation_start']}}</p>
<p>メニュー：{{$data['course_name']}}</p>
<p>店舗名：{{$data['store_name']}}</p>
<p>予約した人：{{$data['name']}}</p>
<br>
<p>■管理画面</p>
<a href="{{url(route('admin.reservation.individual'))}}">{{url(route('admin.reservation.individual'))}}</a><br>
<br>
