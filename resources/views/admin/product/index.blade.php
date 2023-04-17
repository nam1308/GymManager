@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    @foreach($products as $key => $product)
      <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
          <h1 class="card-title">
            {{$product['name']}}
          </h1>
          <p class="card-text">
            {!! $product['description'] !!}
          </p>
          @if($customer && $customer->subscription() && $customer->subscription()->active())
            @if($customer->subscription('default')->stripe_price == $product['id'])
              <div class="alert alert-primary" role="alert">
                現在契約中プラン
              </div>
              <a type="button" class="btn btn-outline-dark btn-lg btn-block" href="{{route('admin.purchase.cancel', $key)}}">解約する</a>
            @else
              <a type="button" class="btn btn-outline-dark btn-lg btn-block" href="{{route('admin.purchase.change', $key)}}">プラン変更する</a>
            @endif
          @else
            <a type="button" class="btn btn-success btn-lg btn-block" href="{{route('admin.purchase.show', $key)}}">購入手続きへ</a>
          @endif
        </div>
      </div>
    @endforeach
  </div>
@endsection
