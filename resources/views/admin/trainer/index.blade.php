@extends('layouts.admin')

@push('javascript-head')
  <script type="module">
		$('.trainer-role').on('change', function () {
			const trainer_id = $(this).data('trainer_id');
			const trainer_role = $(this).val();
			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				url: @json(route('admin.trainer.change-trainer-role')),
				type: 'post',
				dataType: 'json',
				cache: false,
				data: {
					trainer_id: trainer_id,
					trainer_role: trainer_role,
				},
				beforeSend: function () {
				}
			}).done(function (data) {
				console.log(data);
			}).fail(function () {
				alert('時間取得に失敗しました');
			});
		});
  </script>
@endpush

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.trainer') }}
    {{ $trainers->render() }}
    <div class="row">
      <div class="col-12">
        <div class="alert alert-warning" role="alert">
          「トレーナ」にするとラインチャネルに表示されるようになります。<br>
          「非表示」にするとラインチャネルには表示されなくなります。<br>
          <span class="text-danger">※最低１名はトレーナに設定をしてください。</span>
        </div>
        <table class="table table-striped table-bordered table-hover">
          <tbody>
          @foreach ($trainers as $trainer)
            <tr>
              <td>
                <a href="{{ route('admin.trainer.show', $trainer->id) }}"> {{ $trainer->name }} </a>
                <img class="rounded-circle" src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="30" height="30">
              </td>
              <td>{{ $trainer->view_role}}</td>
              <td>
                <select
                    class="form-control form-control-sm trainer-role"
                    name="trainer_role"
                    data-trainer_id="{{$trainer->id}}" @if($admin->role != config('const.ADMIN_ROLE.ADMIN.STATUS')) disabled @endif>
                  @foreach(config('const.TRAINER_ROLE') as $val)
                    <option value="{{$val['STATUS']}}" @if($trainer->trainer_role == $val['STATUS']) selected @endif>{{$val['LABEL']}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    {{ $trainers->render() }}
  </div>
@endsection
