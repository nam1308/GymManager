@extends('layouts.admin')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.purchase.show', $product) }}
    <div class="card" style="margin-bottom: 20px;">
      <div class="card-body">
        <h1>
          {{$product['name']}}
        </h1>
        <p>{{number_format($product['price'])}}円</p>
        @if($customer->subscription('default')->ends_at)
          <div class="alert alert-primary" role="alert">
            <h2>解約されました。</h2>
            <h4>{{$customer->subscription('default')->ends_at}}までご利用できます。</h4>
          </div>
        @else
          <form action="{{route('admin.purchase.cancel', $product_id)}}" method="post">
            @csrf
            <input type="hidden" name="product_id" value="{{$product_id}}">
            <button
                onclick="return confirm('解約しますか？')"
                type="submit"
                class="btn btn-outline-dark btn-lg btn-block"
                href="">解約する
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
@endpush
