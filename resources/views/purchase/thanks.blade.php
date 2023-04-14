@extends('layouts.cart')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1>ご購入ありがとうございました。</h1>
                <a class="btn btn-primary" href="{{route('home')}}">マイページに移動する</a>
            </div>
        </div>
        <br>
        <div class="col-12">
            <div class="row">
                <div class="card" style="width: 18rem;">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap"><title>Placeholder</title>
                        <rect fill="#868e96" width="100%" height="100%"/>
                        <text fill="#dee2e6" dy=".3em" x="50%" y="50%">Image cap</text>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-title">お勧め商品</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap"><title>Placeholder</title>
                        <rect fill="#868e96" width="100%" height="100%"/>
                        <text fill="#dee2e6" dy=".3em" x="50%" y="50%">Image cap</text>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-title">お勧め商品</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap"><title>Placeholder</title>
                        <rect fill="#868e96" width="100%" height="100%"/>
                        <text fill="#dee2e6" dy=".3em" x="50%" y="50%">Image cap</text>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-title">お勧め商品</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary" href="{{route('home')}}">マイページに移動する</a>
            </div>
        </div>
        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p>株式会社メデュ 投資助言・代理業　近畿財務局長（金商）第409号 加入協会：一般社団法人　日本投資顧問業協会　会員番号022‐00283</p>
            <p class="mb-1">&copy; 2020-2021 {{ $basic_setting->company_name }}</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">プライバシーポリシー</a></li>
                <li class="list-inline-item"><a href="#">規約</a></li>
                <li class="list-inline-item"><a href="#">サポート</a></li>
            </ul>
        </footer>
    </div>
@endsection
