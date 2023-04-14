@extends('layouts.admin')
@section('javascript-head')
  <script type="module">
  </script>
@endsection
@section('content')
  <div class="container-fluid">
    @if($trainer)
      {{ Breadcrumbs::render('admin.trainer.edit', $trainer) }}
      <div class="row">
        <div class="col-6">
          <form action="{{ route('admin.trainer.update',$trainer->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <table class="table table-bordered table-striped">
              <thead>
              <th colspan="2">プロフィール編集</th>
              </thead>
              <tbody>
              <tr>
                <th>プロフィール画像</th>
                <td>
                  <input name="profile_photos[]"
                         type="file"
                         id="profile_photos"
                         class="form-control-file @error('profile_photos') is-invalid @enderror"
                         multiple="multiple"
                         accept="image/*">
                  <img src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="200" height="200">
                </td>
              </tr>
              <tr>
                <th>名前</th>
                <td>
                  <input name="name" type="text"
                         class="form-control form-control-lg @error('name') is-invalid @enderror"
                         id="name"
                         value="{{ old('name', $trainer->name) }}" required>
                  @error('name')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </td>
              </tr>
              <tr>
                <th>メールアドレス</th>
                <td><input name="email" type="text"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           id="email"
                           value="{{ $trainer->email }}" disabled>
                  @error('email')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </td>
              </tr>
              <tr>
                <th>管理者権限</th>
                <td>
                  <select
                      name="role"
                      id="role"
                      class="form-control form-control-lg @error('role') is-invalid @enderror" disabled>
                    @foreach(config('const.ADMIN_ROLE') as $key => $val)
                      <option value="{{ $val['STATUS']}}" @if ($val['STATUS'] == $trainer->role)selected @endif> {{ $val['LABEL'] }} </option>
                    @endforeach
                  </select>
                  <!-- 管理者だったら -->
                  <input type="hidden" name="role" value="{{$trainer->role}}">
              </tr>
              <tr>
                <th>トレーナー権限</th>
                <td>
                  <select
                      name="trainer_role"
                      id="trainer_role"
                      class="form-control form-control-lg @error('trainer_role') is-invalid @enderror" @if($admin->role != config('const.ADMIN_ROLE.ADMIN.STATUS')) disabled @endif>
                    @foreach(config('const.TRAINER_ROLE') as $key => $val)
                      <option value="{{ $val['STATUS']}}" @if ($val['STATUS'] == $trainer->trainer_role)selected @endif> {{ $val['LABEL'] }} </option>
                    @endforeach
                  </select>
                </td>
              </tr>
              <tr>
                <th>自己紹介</th>
                <td>
                  <textarea class="form-control" name="self_introduction" rows="5">{{ old('self_introduction', $trainer->self_introduction) }}</textarea>
                  @error('name')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </td>
              </tr>
              </tbody>
            </table>
            <button type="submit" class="btn btn-primary">保存</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.reload()">キャンセル</button>
          </form>
        </div>
      </div>
    @else
      <div class="alert alert-warning" role="alert">
        トレーナーが見つかりません
      </div>
    @endif
  </div>
@endsection
