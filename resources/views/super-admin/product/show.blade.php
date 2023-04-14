@extends('layouts.super-admin')

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('super-admin.product.show', $product) }}
        <div class="row">
            <div class="col-4">
                <table class="table table-bordered table-striped">
                    <thead>
                    <th colspan="2">商品データー</th>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">商品ID</th>
                        <td>{{ $product->code }}</td>
                    </tr>
                    <tr>
                        <th scope="row">商品名</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">価格</th>
                        <td>{{ $product->view_price }}</td>
                    </tr>
                    <tr>
                        <th scope="row">クライアントIP</th>
                        <td>{{ $product->client_ip }}</td>
                    </tr>
                    <tr>
                        <th scope="row">購入数</th>
                        <td>
                            {{$product->view_purchase_count }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">無料期間</th>
                        <td>{{$product->view_free_term }}</td>
                    </tr>
                    <tr>
                        <th scope="row">購入URL</th>
                        <td><a target="_blank" href="{{$product->view_purchase_url}}">{{$product->view_purchase_url}}</a></td>
                    </tr>
                    <tr>
                        <th scope="row">登録日</th>
                        <td>{{$product->view_created_at}}</td>
                    </tr>
                    <tr>
                        <th scope="row">更新日</th>
                        <td>{{$product->view_updated_at}}</td>
                    </tr>
                    <tr>
                        <th scope="row">停止日</th>
                        <td>{{$product->view_deleted_at}}</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2"><a href="{{ route('super-admin.product.edit', $product->id) }}">編集</a></th>
                    </tr>
                    </tbody>
                </table>
                <br>
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#stopModal">停止する</button>
                <button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#copyModal">コピーする</button>
                <!-- Modal -->
                <div class="modal fade" id="stopModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">停止確認</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <strong>「{{ $product->name }}」を停止しますか？</strong>
                                <p>※復旧はいつでもできます。</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">キャンセル</button>
                                {!! Form::open(['route' => ['super-admin.product.destroy', $product->code], 'method' => 'delete']) !!}
                                {!! Form::submit('停止する', ['class' => 'btn btn-danger btn-lg']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal -->
                <div class="modal fade" id="copyModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">確認</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <strong>「{{ $product->name }}」を元に商品をコピーしますか？</strong>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-lg"
                                        data-dismiss="modal">キャンセル
                                </button>
                                {!! Form::open(['route' => ['super-admin.product.copy', $product->id], 'method' => 'post']) !!}
                                {!! Form::submit('コピーする', ['class' => 'btn btn-secondary btn-lg']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </div>
        </div>
    </div>
@endsection
