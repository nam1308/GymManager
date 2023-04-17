@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.trainer') }}
    {{ $trainers->render() }}
    <div class="row">
      <div class="col-12">
        <div class="alert alert-warning" role="alert">
          「トレーナ」にするとラインチャネルに表示されるようになります。<br>
          「非表示」にするとラインチャネルには表示されなくなります。<br>
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
                    @can('staff') disabled @endcan
                class="form-control form-control-sm trainer-role"
                    name="trainer_role"
                    data-trainer_id="{{$trainer->id}}" @if(Auth::guard('admin')->user()->role != config('const.ADMIN_ROLE.ADMIN.STATUS'))
                      disabled
                    @endif>
                  @foreach(config('const.TRAINER_ROLE') as $val)
                    <option value="{{$val['STATUS']}}" @if($trainer->trainer_role == $val['STATUS']) selected @endif>{{$val['LABEL']}}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <button
                    @if($trainer->role == config('const.TRAINER_ROLE.TRAINER.STATUS')) disabled @endif @can('staff') disabled @endcan
                data-trainer_id="{{$trainer->id}}"
                    type="button"
                    class="btn btn-danger btn-sm trainerDeleteButton"> 削除
                </button>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    {{ $trainers->render() }}
  </div>

  <!-- モーダル -->
  <div class="modal" id="trainerDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{ route('admin.trainer.destroy') }}" id="trainerDeleteForm">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            削除すると予約データーなど全てのデーターが削除されます。
            復旧することはできません。
            削除しますか？
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
            <button type="submit" class="btn btn-danger">削除する</button>
          </div>
        </div>
        <input
            type="hidden"
            name="delete_trainer_id"
            id="delete_trainer_id">
        <input
            type="hidden"
            name="vendor_id"
            id="delete_trainer_id"
            value="{{$trainer->vendor_id}}">
      </form>
    </div>
  </div>

  <!-- Modal -->
  <div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">通知</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p id="confirm-title">登録トレーナー数が変更されました。プランの更新を行いますか？</p>
              </div>
              <div class="modal-footer">
                  <form
                      id="form"
                      method="POST"
                      action="{{route('admin.trainer.changePlan')}}"
                      accept-charset="UTF-8">
                      @csrf
                      <button type="submit" id="confirm-btn" class="btn btn-primary">行う</button>
                      <button type="button" id="cancel-btn" class="btn btn-secondary" data-dismiss="modal">行わない</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
@endsection


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

        $('.trainerDeleteButton').on('click', function () {
            $('#trainerDeleteModal').modal('show');
            $('#delete_trainer_id').val($(this).data('trainer_id'));
        });

        $('#trainerDeleteForm').submit(function (event) {
            event.preventDefault();
            const $form = $(this);
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
                }
            }).done(function (data) {
                if(data['changePlan'] == true && data.status == true){
                    $('#trainerDeleteModal').modal('hide');
                    $('#confirm').modal('show');
                } else if(data.status == true && data.changePlan == false) {
                    location.href = data.url;
                } else {
                    alert(data.message);
                }
            }).fail(function () {
                // alert('時間取得に失敗しました');
                alert(data.message);
            });
        });
    </script>
@endpush
