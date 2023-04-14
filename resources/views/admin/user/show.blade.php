@extends('layouts.admin')

@push('javascript-head')
  <script type="module">
		$('#userMemo').submit(function (event) {
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
				timeout: 10000,  // 単位はミリ秒
				beforeSend: function () {
					$button.attr('disabled', true);
					$button.text('送信中...');
				}
			}).done(function (data) {
				console.log(data);
				if (data.status) {
					$('#memoAlertMessage').html('<div class="alert alert-success" role="alert">' + data.message + '</div>');
				}
			}).fail(function (data) {
				$('#memoAlertMessage').html('<div class="alert alert-warning" role="alert">' + data.message + '</div>');
			}).always(function () {
				$button.attr('disabled', false);
				$button.text('保存する');
			});
		});
  </script>
@endpush
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.user.show', $channel_join) }}
    <div class="text-center" style="margin-bottom: 20px;">
      <img src="{{optional($channel_join->user)->picture_url}}" alt="" width="200" class="rounded-circle">
    </div>
    <table class="table table-bordered table-striped">
      <thead>
      <th colspan="2">会員データー</th>
      </thead>
      <tbody>
      <tr>
        <th>ライン名</th>
        <td>{{ $channel_join->user->display_name}}</td>
      </tr>
      <tr>
        <th>名前</th>
        <td>
          {{ $channel_join->user->name}}
        </td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td>{{ $channel_join->user->email }}</td>
      </tr
      <tr>
        <th>電話電話</th>
        <td>{{ $channel_join->user->phone_number }}</td>
      </tr>
      <tr>
        <th>登録日</th>
        <td>{{ $channel_join->user->created_at }}</td>
      </tr>
      <tr>
        <th>更新日</th>
        <td>{{ $channel_join->user->updated_at }}</td>
      </tr>
      <tr>
        <th>削除日</th>
        <td>{{ $channel_join->user->deleted_at }}</td>
      </tr>
      </tbody>
    </table>
    <div class="card">
      <div class="card-header">メモ</div>
      <div class="card-body">
        <form id="userMemo" method="post" action="{{route('admin.user.memo.update', $channel_join->user->id)}}">
          @csrf
          <span id="memoAlertMessage"></span>
          <textarea
              style="margin-bottom: 20px;"
              class="form-control form-control-lg"
              rows="7"
              name="memo">{{old('memo', optional($memo)->memo)}}</textarea>
          <button type="submit" class="btn btn-primary btn-lg">保存する</button>
          <input type="hidden" name="vendor_id" value="{{$channel_join->vendor_id}}">
        </form>
      </div>
    </div>
  </div>
@endsection
