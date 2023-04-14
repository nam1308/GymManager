@extends('layouts.super-admin')

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('super-admin.product') }}
        {{ $products->render() }}
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">商品検索</div>
                    <div class="card-body">
                        {{ Form::open(['url' => route('super-admin.product'), 'class' => 'h-adr', 'method' => 'get']) }}
                        <div class="form-group">
                            <label for="name">商品ID</label>
                            <input
                                    placeholder=""
                                    class="form-control form-control-lg"
                                    autofocus
                                    name="product_code"
                                    type="text"
                                    value="{{ $product_code }}">
                        </div>
                        <div class="form-group">
                            <label for="name">商品名</label>
                            <input
                                    placeholder=""
                                    class="form-control form-control-lg"
                                    name="name"
                                    type="text"
                                    value="{{ $name }}">
                        </div>
                        <div class="form-group">
                            <label for="name">クライアントIP</label>
                            <input
                                    placeholder=""
                                    class="form-control form-control-lg"
                                    name="client_ip"
                                    type="text"
                                    value="{{ $client_ip }}">
                        </div>
                        <button
                                type="submit"
                                name="search"
                                value="true"
                                class="btn btn-primary btn-lg btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            検索する
                        </button>
                        {{ form::close() }}
                    </div>
                </div>
            </div>
            @if(count($products))
                <div class="col-9">
                    {{ $products->render() }}
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>商品ID</th>
                            <th>商品名</th>
                            <th>カテゴリー</th>
                            <th>クライアントIP</th>
                            <th>価格</th>
                            <th>登録日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <a href="{{ route('super-admin.product.show', $product->id) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $product->view_product_category}}
                                </td>
                                <td>
                                    <a href="{{ route('super-admin.user.show', $product->id) }}">{{ $product->client_ip }}</a>
                                </td>
                                <td>
                                    {{ $product->view_price }}
                                </td>
                                <td>{{ $product->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $products->render() }}
                </div>
            @endif
        </div>
    </div>
@endsection
