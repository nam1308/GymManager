@php use App\Models\Product; @endphp
@extends('layouts.admin')
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.invitation.create') }}
    <div class="row">
      <div class="col">
        @if($current_plan['trainer_count'] >= count($trainers) + count($invitations))
          <div class="alert alert-info" role="alert">
            【任意】トレーナーを登録するには招待メールを送ってください。<br>
            招待できるトレーナ数：{{$current_plan['trainer_count']}} / {{count($trainers) + count($invitations)}}人
          </div>
        @else
          <div class="alert alert-warning" role="alert">
            <span class="text-danger">
            現在ご契約中のプランではトレーナを招待することはできません。<br>現在契約中プラン「{{$current_plan['name']}}」
            </span>
          </div>
        @endif
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">{{ __('トレーナー招待') }}</div>
          <div class="card-body">
            <form
                id="form"
                method="POST"
                action="{{route('admin.invitation.store')}}"
                accept-charset="UTF-8">
              @csrf
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">メールアドレス</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            autofocus
                            required
                            name="email"
                            type="email"
                            value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <input
                      id="btn-submit"
                      class="btn btn-success btn-lg"
                      type="submit"
                      value="招待メールを送る">
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>参加中メンバー</th>
            <th>お名前</th>
            <th>システム権限</th>
          </tr>
          </thead>
          <tbody>
          @foreach($trainers as $trainer)
            <tr>
              <td>{{$trainer->email}}</td>
              <td>{{$trainer->name}}</td>
              <td>{{$trainer->view_role}}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('modal')
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
            <p id="confirm-title">Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
            <button type="button" id="confirm-btn" class="btn btn-primary">Save changes</button>
            <button type="button" id="cancel-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
@endsection

@push('javascript-footer')
    <script>
        window.onload = function () {
            const title = '登録トレーナー数が変更されました。プランの更新を行いますか？'
            const confirmText = '行う'
            const cancelText = '行わない'
            $('#confirm-title').text(title)
            $('#confirm-btn').text(confirmText)
            $('#cancel-btn').text(cancelText)
            const currentPlan = @json($current_plan);
            const trainerCount = {{count($trainers) + count($invitations)}}
            const formInput = $('#form')
            let isConfirm = false

            formInput.on('submit',function (e) {
                if (trainerCount === currentPlan.trainer_count){
                    $('#confirm').modal('show')
                    if (!isConfirm){
                        return false
                    }
                }
            })

            $('#confirm-btn').on('click',function (e) {
                isConfirm = true
                formInput.submit()
            })
        }
    </script>
@endpush
