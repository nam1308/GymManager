@extends('layouts.admin')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.purchase.index') }}
    失敗
  </div>
@endsection
@push('javascript-footer')
@endpush

