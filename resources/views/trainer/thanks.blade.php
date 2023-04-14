@extends('layouts.app')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container-fluid">
    <h2>予約申請しました</h2>
    <p class="text-danger">仮予約中です。改めてトレーナからご連絡致します。</p>
    <a href="{{route('reservation', $admin_id)}}" class="btn btn-primary btn-block btn-lg">続けて同じトレーナーで予約する</a>
    <a href="" class="btn btn-primary btn-block btn-lg">別のトレーナーで予約する</a>
    <a href="/reservation" class="btn btn-primary btn-block btn-lg">予約確認・キャンセル</a>
  </div>
@endsection
