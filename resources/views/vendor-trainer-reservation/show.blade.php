@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container">
    @if($trainer)
      {{ Breadcrumbs::render('trainer.show', $trainer) }}
      <span id="errorMessage"></span>
      <form id="reservationForm" method="post" action="{{ route('channel.trainer.reservation.store', [$vendor_id, $trainer_id]) }}">
        @csrf
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            <span class="badge badge-danger">必須</span> 予約申請日
          </div>
          <div class="card-body">
            <p class="card-text">
              <input type="text" class="form-control form-control-lg" id="fCalendar" disabled>
              <input type="hidden" name="date" id="date" value="{{$tomorrow}}">
            </p>
            <span id="scrollTime"></span>
            <label><span class="badge badge-danger">必須</span> 時間</label>
            <select id="times" class="form-control form-control-lg" disabled name="time">
              <option>選択してください</option>
            </select>
          </div>
        </div>
        <span id="scrollShop"></span>
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            <span class="badge badge-danger">必須</span>
            店舗
          </div>
          <div class="card-body">
            <p class="card-text">
              <select id="shop" class="form-control form-control-lg" name="shop">
                <option value="">選択してください</option>
                @foreach($shops as $shop)
                  <option value="{{$shop->id}}">{{$shop->name}}</option>
                @endforeach
              </select>
            </p>
          </div>
        </div>
        <span id="scrollCourse"></span>
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            <span class="badge badge-danger">必須</span>
            メニュー
          </div>
          <div class="card-body">
            <p class="card-text">
              <select id="course" class="form-control form-control-lg" name="course">
                <option value="">選択してください</option>
                @foreach($courses as $course)
                  <option value="{{$course->id}}">{{$course->name}}（{{$course->view_course_time}} | {{$course->view_price}}）</option>
                @endforeach
              </select>
            </p>
          </div>
        </div>
        <span id="scrollTrainer"></span>
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            <span class="badge badge-danger">必須</span>
            トレーナー
          </div>
          <div class="card-body">
            <p class="card-text">
              <img class="rounded-circle" src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="50" height="50">
              {{$trainer->name}}
            </p>
          </div>
        </div>
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">
            質問やお問い合わせなど
          </div>
          <div class="card-body">
            <p class="card-text">
              <textarea
                  rows="3"
                  id="note"
                  name="note"
                  class="form-control form-control-lg @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
            </p>
          </div>
        </div>
        <div style="margin-bottom: 50px;">
          <button
              disabled
              type="submit"
              class="btn btn-primary btn-block btn-lg"
              id="submitButton">予約申請する
          </button>
        </div>
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
		const $times = $('#times');
		const reservationForm = $('#reservationForm');
		reservationForm.on('change', function () {
			const date = $('input[name="date"]').val();
			const time = $('[name="time"] option:selected').val();
			const shop = $('[name="shop"] option:selected').val();// $('input[name="shop"]').val();
			const course = $('[name="course"] option:selected').val();//  $('input[name="course"]').val();
			const $button = $(this).find('button');
			console.log(date);
			console.log(time);
			if (time !== "" && date !== "" && shop !== "" && course !== "") {
				$button.attr('disabled', false);
			} else {
				$button.attr('disabled', true);
			}
		});

		flatpickr('#fCalendar', {
			// dateFormat: 'Y年m月d日',
			minDate: `{{$tomorrow}}`,
			dateFormat: 'Y-m-d',
			inline: true,
			altInput: true,
			altFormat: 'Y年m月d日',
			defaultDate: `{{$tomorrow}}`,
			allowInput: true,
      {{--			disable: @json(json_encode($closes), true),--}}
			disable: @json(($closes)),
			onClose: function (selectedDates, dateStr, instance) {
			},
			onReady: function (selectedDates, dateStr, instance) {
				getTimes(dateStr);
			},
			onChange: function (selectedDates, dateStr, instance) {
				$('#date').val(dateStr);
				const position = $("#scrollTime").offset().top;
				$("html,body").animate({
					scrollTop: position
				}, {
					queue: false
				});
				getTimes(dateStr);
			},
		});

		/**
		 * 予約可能な日付を取得する
		 * @param date
		 */
		function getTimes(date) {
			let option;
			if (date) {
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
						option = `<option data-country="" value="">取得中...</option>`;
						$times.append(option);
					}
				}).done(function (data) {
					$times.html('');
					option = '';
					if (data.length > 0) {
						const dataCount = data.length;
						for (let i = 0; i < dataCount; i++) {
							option += `<option data-time="${data[i]}" value="${data[i]}">${data[i]}</option>`;
						}
					} else {
						option = `<option data-time="" value="">別の日を選んでください</option>`;
					}
					$times.prop('disabled', false);
					$times.append(option);
				}).fail(function () {
					alert('時間取得に失敗しました');
				}).always(function (xhr, msg) {
				});
			} else {
				option = `<option data-time="" value="">別の日を選んでください</option>`;
				$times.append(option);
			}
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
