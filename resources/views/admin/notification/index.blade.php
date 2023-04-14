@extends('layouts.admin')
@push('css')
  <link rel="stylesheet" href="{{asset('css/common.css')}}"></style>
@endpush
@push('javascript-head')
  <script type="module">
		// $('#birthday').on('click', function () {
		// 	const checked = $(this).prop('checked');
		// 	$('#birthday_message').prop('disabled', !checked);
		// });

		let message = '';
		$('#birthday_message').on('blur', function () {
			message = $('#birthday_message').val();
			console.log(message);
		});

		$('#notificationBirthdayForm').submit(function (event) {
			event.preventDefault();
			const $form = $(this);
			const $button = $form.find('button');
			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				url: @json(route('admin.notification.birthday.update')),
				type: 'post',
				dataType: 'json',
				cache: false,
				data: {
					message: message,
				},
				beforeSend: function () {
				}
			}).done(function (data) {
				if (data.status) {
					$('#birthdayMessage').html(`<div class="alert alert-success" role="alert">` + data.message + `</div>`)
				} else {
					$('#birthdayMessage').html(`<div class="alert alert-danger" role="alert">` + data.message + `</div>`)
				}
			}).fail(function () {
				alert('時間取得に失敗しました');
				$('#birthdayMessage').html(`<div class="alert alert-danger" role="alert">' + data.message + '</div>`)
			});
		});
  </script>
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.notification') }}
    <div class="card" style="margin-bottom: 20px">
      <div class="card-header">
        誕生日通知
      </div>
      <div class="card-body">
        <span id="birthdayMessage"></span>
        <h5 class="card-title">お友達になったの誕生日の前日に届くメッセージを登録してください</h5>
        <p class="card-text">※友達ブロックした会員には届きません。</p>
        {{--        <!-- Rounded switch -->--}}
        {{--        <label class="switch">--}}
        {{--          <input type="checkbox" name="birthday" id="birthday" value="on">--}}
        {{--          <spa　n class="slider round"></span>--}}
        {{--        </label>--}}
        <form id="notificationBirthdayForm" method="post" action="{{ route('admin.notification.birthday.update') }}">
        <textarea
            rows="3"
            id="birthday_message"
            name="birthday_message"
            class="form-control form-control-lg @error('birthday_message') is-invalid @enderror">{{optional($birthday)->message}}</textarea>
          <div style="margin-top: 20px;">
            <button
                type="submit"
                class="btn btn-primary btn-block btn-lg"
                id="submitButton">保存する
            </button>
          </div>
        </form>
      </div>
    </div>
    {{--    <div class="card">--}}
    {{--      <div class="card-header">--}}
    {{--        Featured--}}
    {{--      </div>--}}
    {{--      <div class="card-body">--}}
    {{--        <h5 class="card-title">Special title treatment</h5>--}}
    {{--        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>--}}
    {{--        <a href="#" class="btn btn-primary">Go somewhere</a>--}}
    {{--      </div>--}}
    {{--    </div>--}}
  </div>
@endsection
