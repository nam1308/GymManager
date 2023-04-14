@extends('layouts.admin')
@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.invitation.create') }}
    <div class="row">
      <div class="col">
        <div class="alert alert-info" role="alert">
          【任意】トレーナーを登録するには招待メールを送ってください。
        </div>
        <div class="card" style="margin-bottom: 20px;">
          <div class="card-header">{{ __('トレーナー招待') }}</div>
          <div class="card-body">
            {{ Form::open(['url' => route('admin.invitation.store')]) }}
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">メールアドレス</label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <input
                          placeholder="例）example@example.com"
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
                {{ Form::submit('招待メールを送る', ['class' => 'btn btn-success btn-lg']) }}
              </div>
              {{ form::close() }}
            </div>
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
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
