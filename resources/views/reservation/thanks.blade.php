@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    <h2>仮予約申請しました</h2>
    <p class="text-danger"><strong>仮予約中です。改めてトレーナからご連絡致します。</strong></p>
    <a href="{{route('trainer.show', $trainer_id)}}" class="btn btn-primary btn-block btn-lg">続けて同じトレーナーで予約する</a>
    <a href="{{route('trainer')}}" class="btn btn-primary btn-block btn-lg">別のトレーナーで予約する</a>
    <a href="{{route('reservation')}}" class="btn btn-primary btn-block btn-lg">予約確認・キャンセル</a>
  </div>
@endsection
