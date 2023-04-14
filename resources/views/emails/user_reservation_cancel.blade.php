予約がキャンセルされました。<br>
<br>
<br>
-------------------------------------<br>
予約日：{{$data['reservation']}}<br>
名前：{{$data['name']}}<br>
メニュー：{{$data['course_name']}}<br>
店舗：{{$data['shop_name']}}<br>
-------------------------------------<br>
<br>
管理画面<br>
<a href="{{url(route('admin.reservation.individual'))}}">{{url(route('admin.reservation.individual'))}}</a><br>
<br>
@include('emails.common_written')
