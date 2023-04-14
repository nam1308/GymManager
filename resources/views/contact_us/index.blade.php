@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div class="row">
          <div class="col">
            <ul class="list-group">
              <li class="list-group-item">購入履歴</li>
              <li class="list-group-item">設定</li>
              <li class="list-group-item">Q&A</li>
              <li class="list-group-item"><a href="{{ route('contact_us.index') }}">お問い合わせ</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="row justify-content-center">
          <div class="col">
            <div class="card">
              <div class="card-header"> {{ __('お問い合わせ') }} </div>
              <div class="card-body">
                {{ Form::open(['url' => route('contact_us.store'), 'class' => 'h-adr']) }}
                <div class="form-group">
                  <label for="">質問内容 <span class="badge badge-danger">必須</span></label>
                  <select
                      class="form-control form-control-lg p-region-id @error('question_title') is-invalid @enderror"
                      name="question_title">
                    <option value="">選択</option>
                    @foreach (question_title() as $key => $val)
                      <option value="{{ $key }}" @if ($key == old('question_title')) selected @endif>
                        {{ $val }}
                      </option>
                    @endforeach
                  </select>
                  @error('question_title')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="">内容 <span class="badge badge-danger">必須</span></label>
                  <textarea
                      rows="10"
                      name="content"
                      class="form-control form-control-lg @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                  @error('content')
                  <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                  @enderror
                </div>
                <!-- 送信ボタン -->
                {{ Form::submit('送信する', ['class' => 'btn btn-primary btn-lg']) }}
                {{ form::close() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
