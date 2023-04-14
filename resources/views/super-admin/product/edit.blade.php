@extends('layouts.super-admin')

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('super-admin.product.edit', $product) }}
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="col">
                    <div class="card">
                        <div class="card-header">{{ __('商品編集') }}</div>
                        <div class="card-body">
                            {{ Form::open(['url' => route('super-admin.product.update', $product->id), 'class' => 'h-adr']) }}
                            @method('put')
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">商品名 <span class="badge badge-danger">必須</span></label>
                                        <input placeholder=""
                                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                                               autofocus name="name" type="text"
                                               value="{{ old('name', optional($product)->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="name">価格 <span class="badge badge-danger">必須</span></label>
                                                <input placeholder="例）19800"
                                                       class="form-control form-control-lg @error('price') is-invalid @enderror"
                                                       name="price" type="text"
                                                       value="{{ old('price', optional($product)->price) }}">
                                                @error('price')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="name">クライアントIP <span
                                                            class="badge badge-danger">必須</span></label>
                                                <input placeholder="例）111222333"
                                                       class="form-control form-control-lg @error('client_ip') is-invalid @enderror"
                                                       name="client_ip" type="text"
                                                       value="{{ old('client_ip', optional($product)->client_ip) }}">
                                                @error('client_ip')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="name">購入数</label>
                                                <input placeholder="例）50"
                                                       class="form-control form-control-lg @error('purchase_count') is-invalid @enderror"
                                                       name="purchase_count" type="text"
                                                       value="{{ old('purchase_count', optional($product)->purchase_count) }}">
                                                <small class="form-text text-muted">購入数に上限がある場合は半角数字で入力してください。<br>
                                                    例）50の場合は50個売れたら購入ができなります。ここのカウントを増やすだけで上限をコントロールができます。<br>
                                                    「空」にすると無制限となります。</small>
                                                @error('purchase_count')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="name">無料期間</label>
                                                <input placeholder="例）14"
                                                       class="form-control form-control-lg @error('free_term') is-invalid @enderror"
                                                       name="free_term" type="text"
                                                       value="{{ old('free_term', optional($product)->free_term) }}">
                                                <small
                                                        class="form-text text-muted">無料期間がある場合は半角数字で入力してください。<br>例）14の場合は14日間無料</small>
                                                @error('free_term')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::submit('保存する', ['class' => 'btn btn-primary btn-lg']) }}
                            {{ form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6"></div>
@endsection
