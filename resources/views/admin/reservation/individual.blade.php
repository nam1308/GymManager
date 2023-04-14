@extends('layouts.admin')
@push('css')
  <link href='{{ asset('js/fullcalendar-5.8.0/lib/main.min.css') }}' rel='stylesheet'/>
  <link href='{{ asset('css/common.css') }}' rel='stylesheet'/>
  <style>
      .modal {
          max-height: 100%;
          overflow-y: auto;
      }
  </style>
@endpush
@push('javascript-head')
  <script src='{{ asset('js/fullcalendar-5.8.0/lib/main.min.js') }}'></script>
  <script src='{{ asset('js/fullcalendar-5.8.0/lib/locales/ja.js') }}'></script>
@endpush
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.reservation.individual') }}
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
  <!------------------ 確認Modal ------------------------>
  <div class="modal" id="confirmReservationModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
  <!------------------ 確認Modal ------------------------>
  <!------------------ 確予約編集Modal ------------------------>
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
                  <label>予約日 <span class="badge badge-danger">必須</span></label>
                  <input type="text" class="form-control form-control-lg" id="fCalendar" disabled required>
                  <input type="hidden" name="date" id="date" value="{{$today}}">
                </div>
                <div class="form-group">
                  <label>時間 <span class="badge badge-danger">必須</span></label>
                  <select id="times" class="form-control form-control-lg times" name="time" required>
                    <option>選択してください</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>会員 <span class="badge badge-danger">必須</span></label>
                  <select id="user" class="form-control form-control-lg" name="user" required>
                    <option value="">選択してください</option>
                    @foreach ($members as $member)
                      <option value="{{ $member->user_id }}">
                        {{ $member->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>店舗 <span class="badge badge-danger">必須</span></label>
                  <select id="shop" class="form-control form-control-lg" name="shop" required>
                    <option value="">選択してください</option>
                    @foreach ($shops as $shop)
                      <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>メニュー <span class="badge badge-danger">必須</span></label>
                  <select id="course" class="form-control form-control-lg" name="course" required>
                    <option value="">選択してください</option>
                    @foreach ($courses as $course)
                      <option value="{{ $course->id }}">
                        {{ $course->name }}（{{$course->view_course_time}} | {{ $course->view_price }}）
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>予約ステータス</label>
                  <select id="status" class="form-control form-control-lg" name="status" required>
                    <option value="">選択してください</option>
                    @foreach (config('const.RESERVATION_STATUS') as $status)
                      @if($status['LABEL'] != 'キャンセル' && $status['LABEL'] != 'お休み')
                        <option value="{{ $status['STATUS'] }}">{{ $status['LABEL'] }}</option>
                      @endif
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
  <!------------------ 確予約編集Modal ------------------------>
  <!------------------ 新規休憩Modal ------------------------>
  <div class="modal" id="restModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="restForm" method="post" action="{{$store_rest_url}}">
          <div class="modal-header">
            休憩登録
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>開始時間 <span class="badge badge-danger">必須</span></label>
                    <select id="times" class="form-control form-control-lg times" name="restStartTime" required>
                      <option>選択してください</option>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label>終了時間 <span class="badge badge-danger">必須</span></label>
                    <select id="times" class="form-control form-control-lg times" name="restEndTime" required>
                      <option>選択してください</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-danger btn-lg" id="deleteBtn">削除</button> --}}
            <button type="submit" class="btn btn-primary btn-lg">保存する</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!------------------ 新規休憩Modal ------------------------>
  <!------------------ 新規表示Modal -------------------------->
  <div class="modal" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          メニュー
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <h4 id="viewDate"></h4>
            <div class="row">
              <div class="col" style="margin-bottom: 10px;">
                <button type="button" class="btn btn-warning btn-lg btn-block" id="closedBtn">休み登録</button>
              </div>
              <div class="col">
                <button type="button" class="btn btn-warning btn-lg btn-block" id="restBtn">休憩登録</button>
              </div>
            </div>
            <div class="row">
              <div class="col" style="margin-bottom: 10px;">
                <button type="button" class="btn btn-success btn-lg btn-block" id="newReservationBtn">新規予約</button>
              </div>
              <div class="col">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!------------------ 新規表示Modal -------------------------->
  <!------------------ 休みキャンセルModal -------------------------->
  <div class="modal" id="closeCancelModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <h4 id="closeCancelViewDate"></h4>
            <button type="button" class="btn btn-warning btn-lg" id="closedCancelBtn">休みを削除</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!------------------ 休みキャンセルModal -------------------------->
  <!------------------ 休憩キャンセルModal -------------------------->
  <div class="modal" id="restCancelModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          休憩削除
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <h4 id="restCancelViewDate"></h4>
            <button type="button" class="btn btn-warning btn-lg" id="restCancelBtn">休憩を削除</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!------------------ 休憩キャンセルModal -------------------------->
@endsection
@push('javascript-footer')
  <script type="module">
		// 全て埋まったら有効にする
		const restForm = $('#restForm');
		const newReservationForm = $('#newReservationForm');
		const editReservationForm = $('#editReservationForm');
		const viewDeleteBtn = $('#viewDeleteBtn');
		const viewEditBtn = $('#viewEditBtn');
		const viewStatusBtn = $('#viewStatusBtn');
		const newReservationBtn = $('#newReservationBtn');
		const restBtn = $('#restBtn');
		const closedBtn = $('#closedBtn');
		const closedCancelBtn = $('#closedCancelBtn');
		const restCancelBtn = $('#restCancelBtn');

		/**
		 * カレンダー
		 **/
		function viewFlatpickr(dateStr) {
			flatpickr('#fCalendar', {
				// dateFormat: 'Y年m月d日',
				// minDate: 'today',
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
		function getTimes(date) {
			const $times = $('.times');
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
					//selectedDate: date + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds(),
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
				slotMinTime:@if($start_time) @json($start_time) @endif,
				slotMaxTime: @if($end_time) @json($end_time) @endif,
				slotDuration: '00:05:00',
				slotLabelInterval: '00:15',
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
				businessHours: {
					startTime: @if($start_time) @json($start_time) @endif,
					endTime: @if($end_time) @json($end_time) @endif,
					daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
				},
				// カレンダーのイベントを変更できるかどうかを決定します。
				// editable: true,
				dateClick: function (info) { // 新規
					$('#viewDate').html(info.dateStr);
					$('#viewModal').modal('show');
					// 新規登録
					viewEvent(calendar, info);
					// 休み
					closeEvent(calendar, info);
					// 休憩
					viewRestEvent(calendar, info)
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
						console.log("SSSSSSSSSSSSSSSSSSSSSS");
						console.log(data);
						console.log("SSSSSSSSSSSSSSSSSSSSSS");
						if (data.reservation.status_id !== 70) {
							$('#view_date').text(data.reservation.reservation_start)
							$('#view_course_name').text(data.reservation.course_name)
							$('#view_shop_name').text(data.reservation.shop_name)
							$('#view_user_name').text(data.reservation.user_name)
							$('#view_reservation_id').text(info.event.id);
							$('#view_status').text(data.reservation.status);
							$('#date').val(data.reservation.reservation_date);
						}
						// 詳細
						viewReservationEvent(calendar, info, data);
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
					const getUrl = @json($event_url) +'?start=' + info.startStr + '&end=' + info.endStr;
					$.getJSON(getUrl, function (data) {
						console.log('AAAAAAAAAAAA');
						console.log(data);
						console.log('AAAAAAAAAAAA');
						successCallback(data);
					});
				},
        {{-- // @json($event_url), --}}
			})

			calendar.render();
			$('#refresh').on('click', function () {
				// いったんん全部削除
				calendar.removeAllEvents();
				// 最新取得
				calendar.refetchEvents();
			});
		})


		///////////////////////////////////////////////////////////////////////////////////
		// 詳細
		function viewReservationEvent(calendar, info, data) {
			// 休みだったら
			if (data.reservation.status_id === @json(config('const.RESERVATION_STATUS.CLOSE.STATUS'))) {
				$('#closeCancelViewDate').html(data.reservation.reservation_date);
				$('#closeCancelModal').modal('show');
				// 休みキャンセル
				closeCancelEvent(calendar, info);
			} else if (data.reservation.status_id === @json(config('const.RESERVATION_STATUS.REST.STATUS'))) {
				$('#restCancelViewDate').html(data.reservation.reservation_date);
				// 休憩キャンセル
				$('#restCancelModal').modal('show');
				// 休みキャンセル
				restCancelEvent(calendar, info);
			} else {
				// 詳細モーダル
				$('#confirmReservationModal').modal('show');
				// ステータス変更
				const status = @json(config('const.RESERVATION_STATUS.FIXED.STATUS'));
				statusChangeEvent(calendar, info.event.id, status);
				// 削除
				deleteEvent(calendar, info);
				// 編集
				editConfirmEvent(calendar, info);
			}
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
				$('#confirmReservationModal').modal('hide');
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
					console.log('SSSSSSSSS');
					console.log(data);
					console.log('SSSSSSSSS');
					// データー追加
					const event = calendar.getEventById(reservation_id);
					// タイトル変更
					event.setProp('title', data.data.title);
					event.setProp('borderColor', data.data.borderColor);
					// 時間修正
					event.setStart(data.data.start);
					event.setEnd(data.data.end);
					$('#confirmReservationModal').modal('hide');
				}).fail(function (jqXHR, textStatus, errorThrown) {
					alert('削除失敗');
				});
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 却下ボタン
		function deleteEvent(calendar, info) {
			viewDeleteBtn.off('click');
			viewDeleteBtn.on('click', function () {
				deleteProcess(calendar, info);
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 休み削除ボタン
		function closeCancelEvent(calendar, info) {
			closedCancelBtn.off('click');
			closedCancelBtn.on('click', function () {
				deleteProcess(calendar, info);
			});
		}

		function restCancelEvent(calendar, info) {
			restCancelBtn.off('click');
			restCancelBtn.on('click', function () {
				deleteProcess(calendar, info);
			});
		}

		function deleteProcess(calendar, info) {
			if (!info) {
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
					reservationId: info.event.id,
				},
				beforeSend: function () {
				}
			}).done(function (data) {
				const event = calendar.getEventById(info.event.id);
				if (event) {
					event.remove();
				}
				$('#editReservationModal').modal('hide');
				$('#confirmReservationModal').modal('hide');
				$('#closeCancelModal').modal('hide');
				$('#restCancelModal').modal('hide');
			}).fail(function (jqXHR, textStatus, errorThrown) {
				alert('削除失敗');
			});
		}

		/**
		 * 休憩
		 * @param calendar
		 * @param info
		 */
		function viewRestEvent(calendar, info) {
			console.log('休憩された');
			restBtn.off('click');
			restBtn.on('click', function () {
				// 今開いているメニューを閉じる
				$('#viewModal').modal('hide');
				// 時間取得
				getTimes(info.dateStr);
				// 休憩モーダル表示
				$('#restModal').modal('show');
				restEvent(calendar, info);
			});
		}

		function past(dateStr) {
			const now = new Date();
			const today = now.getFullYear() + '-' + Number(now.getMonth() + 1) + '-' + now.getDate();
			if (Date.parse(today) > Date.parse(dateStr)) {
				$('#viewModal').modal('hide');
				alert('過去に予約をすることはできません');
				return false;
			}
			return true;
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 表示
		function viewEvent(calendar, info) {
			newReservationBtn.off('click');
			newReservationBtn.on('click', function () {
				console.log('-------------------------dateClick');
				// 過去だったら新規登録できないようにしている
				if (!past(info.dateStr)) {
					return false;
				}
				$("#deleteBtn").hide();
				// 閉じ
				$('#viewModal').modal('hide');
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
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 休みを設定s
		function closeEvent(calendar, info) {
			console.log('休み');
			closedBtn.off('click');
			closedBtn.on('click', function () {
				// 過去だったら新規登録できないようにしている
				if (!past(info.dateStr)) {
					return false;
				}
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: @json($close_url),
					type: 'post',
					dataType: 'json',
					cache: false,
					data: {
						close_date: info.dateStr,
					},
					timeout: 10000, // 単位はミリ秒
					beforeSend: function () {
					}
				}).done(function (data) {
					if (data.status) {
						// データー追加
						calendar.addEvent(data.data);
						$('#viewModal').modal('hide')
					} else {
						$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
					}
					closedBtn.attr('disabled', false);
				}).fail(function () {
				});
			});
		}

		///////////////////////////////////////////////////////////////////////////////////
		// 編集（保存するボタン押されたら）
		function editEvent(calendar, info) {
			newReservationForm.off('submit');
			newReservationForm.on('submit', function () {
				// 過去だったら新規登録できないようにしている
				if (!past(info.dateStr)) {
					return false;
				}
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
						$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`);
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
		// 休憩処理
		function restEvent(calendar, info) {
			restForm.off('submit').on('submit', function () {
				event.preventDefault();
				const $form = $(this);
				const $button = $form.find('button');
				const $data = $form.serializeArray();
				console.log($data);
				$.ajax({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
					},
					url: $form.attr('action'),
					type: $form.attr('method'),
					dataType: 'json',
					cache: false,
					data: {
						restStartTime: $data[0]['value'],
						restEndTime: $data[1]['value'],
						close_date: info.dateStr,
					},
					timeout: 10000, // 単位はミリ秒
					beforeSend: function () {
						// ボタン押せないように
						$button.attr('disabled', true);
						// option = `<option data-country="" value="">選択してください</option>`;
					}
				}).done(function (data) {
					console.log(data);
					if (data.status) {
						// データー追加
						calendar.addEvent(data.data);
						$('#restModal').modal('hide')
					} else {
						$('#errorMessage').html(`<div class="alert alert-danger" role="alert">` + data
							.message + `</div>`);
					}
					$button.attr('disabled', false);
				}).fail(function (data) {
					console.log(data);
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
					console.log('aaaaaaaaa');
					console.log(data);
					console.log('aaaaaaaaa');
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
