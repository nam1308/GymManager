@extends('layouts.admin')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.profile.edit') }}
    <div class="alert alert-info" role="alert">
      【必須】プロフィール画像、お名前、自己紹介全て入力してください。
    </div>
    <div class="card">
      <div class="card-header">{{ __('プロフィール編集（いつでも編集可能）') }}</div>
      <div class="card-body">
        <form method="POST" action="{{route('admin.profile.update')}}" enctype="multipart/form-data">
          @method('PUT')
          @csrf
          <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('プロフィール画像') }} <span class="badge badge-danger">必須</span></label>
            <div class="col-md-7">
              <img class="rounded-circle" src="{{$admin->profileImage->getPhotoUrl()}}" alt="profile" width="150" height="150">
              <input name="profile_photo"
                     type="file"
                     class="form-control-file @error('profile_photo') is-invalid @enderror">
              <p> 対応ファイル形式：PNG,JPG,JPEG,GIF
                ファイルサイズ：5MB以内</p>
              @error('profile_photo')
              <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('お名前') }} <span class="badge badge-danger">必須</span></label>
            <div class="col-md-7">
              <input id="name"
                     class="form-control form-control-lg p-postal-code @error('name') is-invalid @enderror"
                     name="name"
                     type="text"
                     value="{{ old('name', $admin->name) }}">
              @error('name')
              <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('自己紹介') }} <span class="badge badge-danger">必須</span></label>
            <div class="col-md-7">
                    <textarea
                        class="form-control form-control-lg @error('self_introduction') is-invalid @enderror"
                        name="self_introduction"
                        rows="7">{{ old('self_introduction', $admin->self_introduction) }}</textarea>
              @error('self_introduction')
              <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('トレーナー権限') }} <span class="badge badge-danger">必須</span></label>
            <div class="col-md-7">
              <select class="form-control form-control-lg" id="trainer_role" name="trainer_role">
                @foreach(config('const.TRAINER_ROLE') as $key => $val)
                  <option value="{{ $val['STATUS']}}"> {{ $val['LABEL'] }} </option>
                @endforeach
              </select>
              <small>
                非表示を「トレーナー」にするとライン側でトレーナーとして表示されるようになります。
                設定はいつでも変更することができます。
              </small>
              @error('self_introduction')
              <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
              @enderror
            </div>
          </div>
          <div class="form-group row mb-0">
            <div class="col-md-9 offset-md-3">
              <button type="submit" class="btn btn-primary btn-lg"> {{ __('保存') }} </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
@endpush
