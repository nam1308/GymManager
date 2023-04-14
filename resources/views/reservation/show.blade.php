@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container">
    @if($reservation)
      {{ Breadcrumbs::render('reservation.show', $reservation) }}
      <table class="table table-bordered">
        <tbody>
        <tr>
          <th>予約日</th>
        </tr>
        <tr>
          <td><h4>{{$reservation->start_date}} {{$reservation->start_time}} 〜 {{$reservation->end_time}}</h4></td>
        </tr>
        <tr>
          <th>トレーナー</th>
        </tr>
        <tr>
          <td>
            <img class="rounded-circle" src="{{$reservation->admin->profileImage->getPhotoUrl()}}" alt="profile" width="100" height="100">
            {{$reservation->admin->name}}
          </td>
        </tr>
        <tr>
          <th>メニュー</th>
        </tr>
        <tr>
          <td>{{$reservation->course->name}}（{{$reservation->course->view_course_time}}）</td>
        </tr>
        <tr>
          <th>店舗</th>
        </tr>
        <tr>
          <td>
            住所：〒{{$reservation->shop->postal_code}}<br>
            {{$reservation->shop->view_address}}
          </td>
        </tr>
        <tr>
          <th>電話番号</th>
        </tr>
        <tr>
          <td>
            <a href="tel:{{$reservation->shop->phone_number}}">{{$reservation->shop->phone_number}}</a>
          </td>
        </tr>
        <tr>
          <th>URL</th>
        </tr>
        <tr>
          <td><a href="{{$reservation->shop->url}}" target="_blank">{{$reservation->shop->url}}</a></td>
        </tr>
        </tbody>
      </table>
      <!-- Button trigger modal -->
      <div style="margin-bottom: 50px;">
        <button type="button" class="btn btn-lg btn-danger btn-block" data-toggle="modal" data-target="#exampleModalCenter">
          予約キャンセル
        </button>
      </div>
      <!-- Modal -->
      <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <form id="reservationCancelForm" method="post" action="{{ route('reservation.cancel', $reservation->id) }}">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">予約シャンセル確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <span id="alertMessage"></span>
                予約日<br>
                「{{$reservation->reservation_start}}」をキャンセルしますか？
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">閉じる</button>
                <button type="submit" class="btn btn-danger btn-lg">キャンセル</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        予約はありません
      </div>
    @endif
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
		const reservationCancelForm = $('#reservationCancelForm');
		/**
		 * 予約
		 */
		reservationCancelForm.submit(function (event) {
			event.preventDefault();
			const $form = $(this);
			const $button = $form.find('button');
			console.log($form.attr('action'));
			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				url: $form.attr('action'),
				type: $form.attr('method'),
				dataType: 'json',
				cache: false,
				data: $form.serialize(),
				timeout: 10000,  // 単位はミリ秒
				beforeSend: function () {
					// ボタン押せないように
					$button.attr('disabled', true);
				}
			}).done(function (data) {
				console.log(data);
				if (data.status) {
					$('#alertMessage').html(`<div class="alert alert-success" role="alert">` + data.message + `</div>`);
					setTimeout(() => {
						window.location.href = data.url;
					}, 500);
				} else {
					$('#alertMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
				}
			}).fail(function (data) {
				$('#alertMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
			}).always(function () {
			});
		});
  </script>
@endpush
