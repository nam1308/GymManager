@extends('layouts.admin')
@push('css')
  <link href='{{ asset('js/fullcalendar-5.8.0/lib/main.min.css') }}' rel='stylesheet'/>
  <link href='{{ asset('css/common.css') }}' rel='stylesheet'/>
@endpush
@push('javascript-head')
  <script src='{{ asset('js/fullcalendar-5.8.0/lib/main.min.js') }}'></script>
  <script src='{{ asset('js/fullcalendar-5.8.0/lib/locales/ja.js') }}'></script>
@endpush
@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('admin.reservation') }}
    <div class="row">
      <div class="col-12">
        <button class="btn btn-sm btn-primary" id="refresh">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise"
               viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
            <path
                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
          </svg>
        </button>
        <div id='calendar' style="margin-bottom: 150px;"></div>
      </div>
    </div>
  </div>
  <!-- 確認Modal -->
  <div class="modal" id="viewReservationModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">確認</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            @csrf
            <div class="form-group">
              <label>ステータス</label>
              <div id="view_status"></div>
            </div>
            <div class="form-group">
              <label>予約日</label>
              <div id="view_date"></div>
            </div>
            <div class="form-group">
              <label>会員</label>
              <div id="view_user_name"></div>
            </div>
            <div class="form-group">
              <label>店舗</label>
              <div id="view_shop_name"></div>
            </div>
            <div class="form-group">
              <label>メニュー</label>
              <div id="view_course_name"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-lg" id="viewDeleteBtn">却下</button>
          <button type="button" class="btn btn-warning btn-lg" id="viewEditBtn">予約編集</button>
          <button type="button" class="btn btn-success btn-lg" id="viewStatusBtn">確定</button>
        </div>
        <input type="hidden" name="view_reservation_id" id="view_reservation_id" value="">
      </div>
    </div>
  </div>
  <!-- 確認Modal -->
  <!-- 新規Modal -->
  <div class="modal" id="editReservationModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="newReservationForm" method="post" action="">
          <div class="modal-header">
            <h5 class="modal-title" id="reservationModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card" style="margin-bottom: 20px;">
              <div class="card-body">
                @csrf
                <div class="form-group">
                  <label>予約日</label>
                  <input type="text" class="form-control form-control-lg" id="fCalendar" required disabled>
                  <input type="hidden" name="date" id="date" value="{{$today}}">
                </div>
                <div class="form-group">
                  <label>時間</label>
                  <select id="times" class="form-control form-control-lg" name="time" required>
                    <option>選択してください</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>会員</label>
                  <select id="user" class="form-control form-control-lg" name="user" required>
                    <option value="">選択してください</option>
                    @foreach ($members as $member)
                      <option value="{{ $member->user_id }}">
                        {{ $member->user->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>店舗</label>
                  <select id="shop" class="form-control form-control-lg" name="shop" required>
                    <option value="">選択してください</option>
                    @foreach ($shops as $shop)
                      <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>メニュー</label>
                  <select id="course" class="form-control form-control-lg" name="course" required>
                    <option value="">選択してください</option>
                    @foreach ($courses as $course)
                      <option value="{{ $course->id }}">
                        {{ $course->name }}（{{ $course->view_price }}）
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>予約ステータス</label>
                  <select id="status" class="form-control form-control-lg" name="status" required>
                    <option value="">選択してください</option>
                    @foreach (config('const.RESERVATION_STATUS') as $status)
                      <option value="{{ $status['STATUS'] }}">{{ $status['LABEL'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-danger btn-lg" id="deleteBtn">削除</button> --}}
            <button type="submit" class="btn btn-primary btn-lg">保存する</button>
          </div>
          <input type="hidden" name="reservation_id" id="reservation_id" value="">
        </form>
      </div>
    </div>
  </div>
  <!-- 新規Modal -->
@endsection
@push('javascript-footer')
  <script type="module">
		// 全て埋まったら有効にする
		const newReservationForm = $('#newReservationForm');
		const editReservationForm = $('#editReservationForm');
		const viewDeleteBtn = $('#viewDeleteBtn');
		const viewEditBtn = $('#viewEditBtn');
		const viewStatusBtn = $('#viewStatusBtn');

		/**
		 * カレンダー
		 **/
		function viewFlatpickr(dateStr) {
			flatpickr('#fCalendar', {
				// dateFormat: 'Y年m月d日',
				minDate: `{{$today}}`,
				dateFormat: 'Y-m-d',
				altInput: true,
				inline: true,
				altFormat: 'Y年m月d日',
				// defaultDate: '"' + dateStr + '"',
				defaultDate: dateStr,
				allowInput: true,
				onClose: function (selectedDates, dateStr, instance) {
					// console.log(dateStr);
					// getTimes(dateStr);
				},
				onReady: function (selectedDates, dateStr, instance) {
				},
				onChange: function (selectedDates, dateStr, instance) {
					$('#date').val(dateStr);
					getTimes(dateStr);
				},
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
    {{-- function getReservation(reservationId) { --}}
    {{-- $.ajax({ --}}
    {{-- headers: { --}}
    {{-- "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") --}}
    {{-- }, --}}
    {{-- url: @json(route('admin.reservation.getEvent')), --}}
    {{-- type: 'get', --}}
    {{-- dataType: 'json', --}}
    {{-- cache: false, --}}
    {{-- data: { --}}
    {{-- reservationId: reservationId, --}}
    {{-- }, --}}
    {{-- beforeSend: function () { --}}
    {{-- } --}}
    {{-- }).done(function (data) { --}}

    {{-- }).fail(function (jqXHR, textStatus, errorThrown) { --}}
    {{-- alert('予約取得に失敗しました'); --}}
    {{-- }); --}}
    {{-- } --}}

		///////////////////////////////////////////////////////////////////////////////////
		function getTimes(date) {
			const $times = $('#times');
			let options;
			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				url: `{{ $times_url }}`,
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
				if (data.length > 0) {
					const dataCount = data.length;
					for (let i = 0; i < dataCount; i++) {
						options += `<option data-time="${data[i]}" value="${data[i]}">${data[i]}</option>`;
					}
					$times.html('');
					$times.prop('disabled', false);
					$times.append(options);
				} else {
					options = `<option data-time="" value="">時間取得失敗</option>`;
				}
			}).fail(function (jqXHR, textStatus, errorThrown) {
				alert('時間取得に失敗しました');
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		document.addEventListener('DOMContentLoaded', function () {
			const calendarEl = document.getElementById('calendar');
			const calendar = new FullCalendar.Calendar(calendarEl, {
				// ブラウザーのタイムゾーン
				timeZone: 'local',
				// 日本
				locale: 'ja',
				// カレンダーをスクロールなしの全量表示したい
				contentHeight: 'auto',
				initialView: 'dayGridMonth',
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay'
				},
				// 開始
				slotMinTime: "08:00:00",
				slotDuration: '00:15:00',
				slotLabelInterval: '00:30',
				nowIndicator: true,
				// eventDisplay: 'block',
				navLinks: true,
				slotLabelFormat: {
					hour: 'numeric',
					minute: '2-digit',
					omitZeroMinute: false,
					meridiem: 'short'
				},
				// カレンダーの特定の時間枠を強調します。デフォルトでは、月曜日から金曜日の午前9時から午後5時までです。
				// 営業時間
				// https://fullcalendar.io/docs/businessHours
				businessHours: true,
				// カレンダーのイベントを変更できるかどうかを決定します。
				// editable: true,
				dateClick: function (info) { // 新規
					$("#deleteBtn").hide();
					console.log('-------------------------dateClick');
					// 初期化
					$('#newReservationForm').find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
					// タイトル変更
					$('#reservationModalTitle').text('新規予約');
					// からにする
					$('#reservation_id').val('');
					// クリックされた日時
					$('#date').val(info.dateStr);
					// action先を修正
					newReservationForm.attr('action', @json(route('admin.reservation.store')));
					// 時間取得
					getTimes(info.dateStr);
					// モーダル
					$('#editReservationModal').modal('show');
					// 少し遅らせる
					setTimeout(() => {
						viewFlatpickr(info.dateStr.toString());
					});
					addEvent(calendar, info);
				},
				eventClick: function (info) { // 編集
					console.log('-------------------------eventClick');
					$('#view_date').text('取得中...');
					$('#view_course_name').text('取得中...');
					$('#view_shop_name').text('取得中...');
					$('#view_user_name').text('取得中...');
					$('#view_reservation_id').text('');
					$('#view_status').text('取得中...');
					$.ajax({
						headers: {
							"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
						},
						url: @json(route('admin.reservation.getViewEvent')),
						type: 'get',
						dataType: 'json',
						cache: false,
						data: {
							reservationId: info.event.id,
						},
						beforeSend: function () {
						}
					}).done(function (data) {
						$('#view_date').text(data.reservation.reservation_start)
						$('#view_course_name').text(data.reservation.course_name)
						$('#view_shop_name').text(data.reservation.shop_name)
						$('#view_user_name').text(data.reservation.user_name)
						$('#view_reservation_id').text(info.event.id);
						$('#view_status').text(data.reservation.status);
						// 詳細
						viewReservationEvent(calendar, info);
					}).fail(function () {
						alert('予約取得に失敗しました');
					});
					// // 詳細
					// $('#editReservationModal').modal('show');
				},
				updateEvent: function (event, element) {
					console.log('--------------updateEvent');
				},
				eventDrop: function (info) {
					console.log('--------------eventDrop');
					if (!confirm("Are you sure about this change?")) {
						info.revert();
					}
				},
				events: function (info, successCallback, failureCallback) {
					const getUrl = @json($event_url) +'?start=' + info.startStr + '&end=' + info
						.endStr;
					$.getJSON(getUrl, function (data) {
						successCallback(data);
					});
				}
        {{-- // @json($event_url), --}}
			});
			calendar.render();
			$('#refresh').on('click', function () {
				calendar.refetchEvents();
			});
		});

		///////////////////////////////////////////////////////////////////////////////////
		// 詳細
		function viewReservationEvent(calendar, info) {
			// 詳細モーダル
			$('#viewReservationModal').modal('show');
			// ステータス変更
			const status = @json(config('const.RESERVATION_STATUS.FIXED.STATUS'));
			statusChangeEvent(calendar, info.event.id, status);
			// 削除
			deleteEvent(calendar, info.event.id);
			// 編集
			editConfirmEvent(calendar, info);
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 予約編集ボタンが押されたら
		function editConfirmEvent(calendar, info) {
			viewEditBtn.off('click');
			viewEditBtn.on('click', function () {
				// 初期化
				$('#newReservationForm').find("textarea, :text, select").val("").end().find(":checked").prop(
					"checked", false);
				// からにする
				$('#reservation_id').val(info.event.id);
				// 表示
				$('#editReservationModal').modal('show');
				// 非表示
				$('#viewReservationModal').modal('hide');
				// タイトル変更
				$('#reservationModalTitle').text('編集');
				// URL変更
				newReservationForm.attr('action', @json(route('admin.reservation.update')));
				// 時間取得
				getTimes(info.dateStr);
				// 編集データーをとってくる
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: @json(route('admin.reservation.getEvent')),
					type: 'get',
					dataType: 'json',
					cache: false,
					data: {
						reservationId: info.event.id,
					},
					beforeSend: function () {
					}
				}).done(function (data) {
					if (data.reservation) {
						$("#deleteBtn").show();
						const reservation_start = data.reservation.reservation_start.split(' ');
						setTimeout(() => {
							viewFlatpickr(reservation_start[0]);
						});
						$('#times option[value="' + reservation_start[1] + '"]').prop('selected', true);
						$('#user option[value="' + data.reservation.user_id + '"]').prop('selected', true);
						$('#shop option[value=' + data.reservation.shop_id + ']').prop('selected', true);
						$('#course option[value=' + data.reservation.course_id + ']').prop('selected',
							true);
						$('#status option[value=' + data.reservation.status + ']').prop('selected', true);
					}
					editEvent(calendar, info);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					alert('予約取得に失敗しました');
				});
			});
		}

		/**
		 * 変更処理
		 * @param calendar
		 * @param reservation_id
		 * @param status
		 */
		function statusChangeEvent(calendar, reservation_id, status) {
			// ステータス変更
			viewStatusBtn.off('click');
			// ステータス
			viewStatusBtn.on('click', function () {
				if (!reservation_id) {
					alert('削除対象が見つかりません');
				}
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: `{{ $status_url }}`,
					type: 'post',
					dataType: 'json',
					cache: false,
					data: {
						reservationId: reservation_id,
						status: status,
					},
					beforeSend: function () {
					}
				}).done(function (data) {
					console.log(data);
					// データー追加
					const event = calendar.getEventById(reservation_id);
					// タイトル変更
					event.setProp('title', data.data.title);
					event.setProp('borderColor', data.data.borderColor);
					// 時間修正
					event.setStart(data.data.start);
					event.setEnd(data.data.end);
					$('#viewReservationModal').modal('hide');
				}).fail(function (jqXHR, textStatus, errorThrown) {
					alert('削除失敗');
				});
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		function deleteEvent(calendar, reservation_id) {
			viewDeleteBtn.off('click');
			viewDeleteBtn.on('click', function () {
				if (!reservation_id) {
					alert('削除対象が見つかりません');
				}
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: `{{ $destroy_url }}`,
					type: 'post',
					dataType: 'json',
					cache: false,
					data: {
						reservationId: reservation_id,
					},
					beforeSend: function () {
					}
				}).done(function (data) {
					const event = calendar.getEventById(reservation_id);
					if (event) {
						event.remove();
					}
					$('#editReservationModal').modal('hide');
					$('#viewReservationModal').modal('hide');
				}).fail(function (jqXHR, textStatus, errorThrown) {
					alert('削除失敗');
				});
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 編集（保存するボタン押されたら）
		function editEvent(calendar, info) {
			newReservationForm.off('submit');
			newReservationForm.on('submit', function () {
				event.preventDefault();
				const $form = $(this);
				const $button = $form.find('button');
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: $form.attr('action'),
					type: $form.attr('method'),
					dataType: 'json',
					cache: false,
					data: $form.serialize(),
					timeout: 10000, // 単位はミリ秒
					beforeSend: function () {
						// ボタン押せないように
						$button.attr('disabled', true);
						// option = `<option data-country="" value="">選択してください</option>`;
					}
				}).done(function (data) {
					if (data.status) {
						// データー追加
						const event = calendar.getEventById(info.event.id);
						// タイトル変更
						event.setProp('title', data.data.title);
						event.setProp('borderColor', data.data.borderColor);
						// 時間修正
						event.setStart(data.data.start);
						event.setEnd(data.data.end);
						$('#editReservationModal').modal('hide');
					} else {
						$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data
							.message + `</div>`);
					}
					$button.attr('disabled', false);
				}).fail(function () {
					$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message +
						`</div>`);
					// ボタンメッセージを戻す
					$button.attr('disabled', false);
				});
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 新規
		function addEvent(calendar) {
			newReservationForm.off('submit');
			newReservationForm.on('submit', function () {
				event.preventDefault();
				const $form = $(this);
				const $button = $form.find('button');
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: $form.attr('action'),
					type: $form.attr('method'),
					dataType: 'json',
					cache: false,
					data: $form.serialize(),
					timeout: 10000, // 単位はミリ秒
					beforeSend: function () {
						// ボタン押せないように
						$button.attr('disabled', true);
						// option = `<option data-country="" value="">選択してください</option>`;
					}
				}).done(function (data) {
					if (data.status) {
						// データー追加
						calendar.addEvent(data.data);
						$('#editReservationModal').modal('hide')
					} else {
						$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data
							.message + `</div>`);
					}
					$button.attr('disabled', false);
				}).fail(function () {
					$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message +
						`</div>`);
					// ボタンメッセージを戻す
					$button.attr('disabled', false);
				});
			});
		}
  </script>
@endpush
