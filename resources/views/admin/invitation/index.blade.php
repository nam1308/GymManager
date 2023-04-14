@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row">
      {{--      <div class="col-3">--}}
      {{--        <div class="card">--}}
      {{--          <div class="card-header">トレーナ検索</div>--}}
      {{--          <div class="card-body">--}}
      {{--            {{ Form::open(['url' => route('admin.trainer'), 'class' => 'h-adr']) }}--}}
      {{--            <div class="form-group">--}}
      {{--              <label for="name">メールアドレス</label>--}}
      {{--              <input placeholder="例）example@example.com" class="form-control form-control-lg" name="email" type="text" value="">--}}
      {{--            </div>--}}
      {{--            <button type="submit" class="btn btn-primary btn-lg btn-block">--}}
      {{--              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">--}}
      {{--                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>--}}
      {{--              </svg>--}}
      {{--              検索する--}}
      {{--            </button>--}}
      {{--            {{ form::close() }}--}}
      {{--          </div>--}}
      {{--        </div>--}}
      {{--      </div>--}}
      <div class="col-12">
        {{ Breadcrumbs::render('admin.invitation') }}
        @if(count(@$invitations) > 0)
          <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
              <th>メールアドレス</th>
              <th>招待日</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($invitations as $invitation)
              <tr>
                <td>
                  {{ $invitation->email }}
                </td>
                <td>
                  {{ $invitation->created_at}}
                </td>
                <td>
                  <form method="POST" action="{{ route('admin.invitation.retransmission') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">再送信</button>
                    <input type="hidden" name="invitation_id" value="{{$invitation->id}}">
                  </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning" role="alert">
            データーはありません。
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
