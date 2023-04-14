@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    @if($trainer)
      {{ Breadcrumbs::render('reservation.create', $trainer) }}
      <h2>予約申請</h2>
      <span id="errorMessage"></span>
      <p>トレーナ名</p>
      {{$trainer->name}}
      <hr>
      <form id="reservationForm" method="post" action="{{ route('reservation.store', $trainer_id) }}">
        @csrf
        <div class="form-group">
          <label>予約申請日</label>
          <input type="text" class="form-control form-control-lg" id="fCalendar" name="date">
          <small class="form-text text-muted">予約申請を選択してください</small>
        </div>
        <div class="form-group">
          <label>時間</label>
          <select id="times" class="form-control form-control-lg" disabled name="time">
            <option>選択してください</option>
          </select>
        </div>
        <div class="form-group">
          <label>店舗</label>
          <select id="shop" class="form-control form-control-lg" name="shop">
            <option value="">選択してください</option>
            @foreach($shops as $shop)
              <option value="{{$shop->id}}">{{$shop->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>メニュー</label>
          <select id="course" class="form-control form-control-lg" name="course">
            <option value="">選択してください</option>
            @foreach($courses as $course)
              <option value="{{$course->id}}">{{$course->name}}（{{$course->view_price}}）</option>
            @endforeach
          </select>
        </div>
        <button
            disabled
            type="submit"
            class="btn btn-primary btn-block btn-lg"
            id="submitButton">予約申請する
        </button>
      </form>
    @else
      <div class="alert alert-warning" role="alert">
        チャンネル登録されていません
      </div>
    @endif
  </div>
@endsection
@push('javascript-footer')
  <script type="module">
		const reservationForm = $('#reservationForm');

		reservationForm.on('change', function () {
			const date = $('input[name="date"]').val();
			const time = $('[name="time"] option:selected').val();
			const shop = $('[name="shop"] option:selected').val();// $('input[name="shop"]').val();
			const course = $('[name="course"] option:selected').val();//  $('input[name="course"]').val();
			const $button = $(this).find('button');
			if (time !== "" && date !== "" && shop !== "" && course !== "") {
				$button.attr('disabled', false);
			} else {
				$button.attr('disabled', true);
			}
		});

		flatpickr('#fCalendar', {
			// dateFormat: 'Y年m月d日',
			dateFormat: 'Y-m-d',
			altInput: true,
			altFormat: "Y年m月d日",
			onChange: function (selectedDates, dateStr, instance) {
				getTimes(dateStr);
			}
		});

		/**
		 * 予約可能な日付を取得する
		 * @param date
		 */
		function getTimes(date) {
			const $times = $('#times');
			let option = `<option data-time="" value="">選択してください</option>`;
			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				url: `{{$times_url}}`,
				type: 'get',
				dataType: 'json',
				cache: false,
				data: {
					selectedDate: date,
				},
				beforeSend: function () {
					$times.html('');
					let option = `<option data-country="" value="">選択してください</option>`;
					$times.append(option);
				}
			}).done(function (data) {
				if (data.times.length > 0) {
					const dataCount = data.times.length;
					for (let i = 0; i < dataCount; i++) {
						option += `<option data-time="${data.times[i]}" value="${data.times[i]}">${data.times[i]}</option>`;
					}
				} else {
					option = `<option data-time="" value="">時間取得失敗</option>`;
				}
				$times.html('');
				$times.prop('disabled', false);
				$times.append(option);
			}).fail(function (jqXHR, textStatus, errorThrown) {
				alert('時間取得に失敗しました');
			}).always(function (xhr, msg) {
				console.log('完了');
			});
		}

		/**
		 * 予約
		 */
		reservationForm.submit(function (event) {
			event.preventDefault();
			const $form = $(this);
			const $button = $form.find('button');
			let option = '';
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
					$button.text('送信中...');
					option = `<option data-country="" value="">選択してください</option>`;
				}
			}).done(function (data) {
				console.log(data);
				if (data.status) {
					window.location.href = data.url;
				} else {
					$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
					$button.attr('disabled', false);
					// ボタンメッセージを戻す
					$button.text('予約申請する');
					$button.attr('disabled', false);
				}
			}).fail(function () {
				$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
				// ボタンメッセージを戻す
				$button.text('予約申請する');
				$button.attr('disabled', false);
			}).always(function () {
			});
		});
  </script>
@endpush
