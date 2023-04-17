@extends('layouts.admin')
@push('css')
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.purchase.show', $new_product) }}
    <div class="card" style="margin-bottom: 20px;">
      <div class="card-body">
        <h1>
          {{$new_product['name']}}
        </h1>
        <p>{{number_format($new_product['price'])}}円</p>
        @if($customer->subscription('default')->ends_at)
          <div class="alert alert-primary" role="alert">
            <h2>解約されました。</h2>
            <h4>{{$customer->subscription('default')->ends_at}}までご利用できます。</h4>
          </div>
        @else
          <form action="{{route('admin.purchase.change', $product_id)}}" method="post">
            @csrf
            <input type="hidden" name="product_id" value="{{$product_id}}">
            <button
                onclick="return confirm('プラン変更しますか？')"
                type="submit"
                class="btn btn-outline-dark btn-lg btn-block"
                href="">プラン変更
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
@endpush
