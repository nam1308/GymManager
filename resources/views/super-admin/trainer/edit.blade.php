@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid" style="margin-bottom: 100px;">
    @if($trainer)
      {{ Breadcrumbs::render('super-admin.trainer.show', $trainer) }}
      <div class="row justify-content-md-center">
        <div class="col col-lg-8">
          <form action="{{route('super-admin.trainer.update', $trainer->id)}}" method="post">
            @method('put')
            @csrf
            <table class="table table-striped table-bordered table-hover">
              <tbody>
              <tr>
                <th>プロフィール画像</th>
                <td><img class="rounded-circle" src="{{ $trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200"></td>
              </tr>
              <tr>
                <th>トレーナID</th>
                <td>
                  {{ $trainer->id}}</td>
              </tr>
              <tr>
                <th>店舗ID</th>
                <td>{{ $trainer->vendor_id}}</td>
              </tr>
              <tr>
                <th>ログインID</th>
                <td>
                  <input
                      disabled
                      type="text"
                      class="form-control @error('login_id') is-invalid @enderror"
                      value="{{old('login_id', $trainer->login_id)}}"
                      name="login_id"
                      required>
                </td>
              </tr>
              <tr>
                <th>名前</th>
                <td>
                  <input
                      type="text"
                      class="form-control @error('name') is-invalid @enderror"
                      value="{{old('name', $trainer->name)}}"
                      name="name"
                      required>
                </td>
              </tr>
              <tr>
                <th>メールアドレス</th>
                <td>
                  <input
                      type="text"
                      disabled
                      class="form-control @error('email') is-invalid @enderror"
                      value="{{old('email', $trainer->email)}}"
                      name="email">
                </td>
              </tr>
              <tr>
                <th>パスワード</th>
                <td>
                  <input
                      type="text"
                      class="form-control form-control-lg @error('password') is-invalid @enderror"
                      value="{{old('password')}}"
                      name="password">
                </td>
              </tr>
              <tr>
                <th>システム権限</th>
                <td>
                  <select
                      name="role"
                      id="role"
                      class="form-control form-control-lg @error('role') is-invalid @enderror">
                    @foreach(config('const.ADMIN_ROLE') as $key => $val)
                      <option value="{{ $val['STATUS']}}" @if ($val['STATUS'] == $trainer->role)selected @endif> {{ $val['LABEL'] }} </option>
                    @endforeach
                  </select>
                </td>
              </tr>
              <tr>
                <th>トレーナー</th>
                <td>
                  <select
                      name="trainer_role"
                      id="trainer_role"
                      class="form-control form-control-lg @error('trainer_role') is-invalid @enderror" @if($trainer->role != config('const.ADMIN_ROLE.ADMIN.STATUS')) disabled @endif>
                    @foreach(config('const.TRAINER_ROLE') as $key => $val)
                      <option value="{{ $val['STATUS']}}" @if ($val['STATUS'] == $trainer->trainer_role)selected @endif> {{ $val['LABEL'] }} </option>
                  @endforeach
                </td>
              </tr>
              <tr>
                <th>自己紹介</th>
                <td>
                  <textarea
                      disabled
                      class="form-control form-control-lg @error('self_introduction') is-invalid @enderror"
                      name="self_introduction"
                      rows="7">{{ old('self_introduction', $trainer->self_introduction) }}</textarea>
                  @error('self_introduction')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </td>
              </tr>
              <tr>
                <th>登録日</th>
                <td>{{ $trainer->created_at }}</td>
              </tr>
              <tr>
                <th>更新日</th>
                <td>{{ $trainer->updated_at }}</td>
              </tr>
              </tbody>
            </table>
            <button type="submit" class="btn btn-primary btn-lg">
              保存する
            </button>
          </form>
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
  </div>
@endsection
