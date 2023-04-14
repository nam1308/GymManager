@extends('layouts.super-admin')

@section('content')
    <div class="container-fluid">
        {{ Breadcrumbs::render('super-admin.admin-user.user-index', $vendor) }}
        {{ $users->render() }}
        <div class="row">
            <div class="col-9">
                @if(count($users) > 0)
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>名前</th>
                            <th>メールアドレス</th>
                            <th>登録日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td><a href="#"> {{ $user->name }} </a></td>
                                <td>{{ $user->email }}</a></td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning" role="alert">
                        データーはありません。
                    </div>
                @endif
            </div>
        </div>
        {{ $users->render() }}
    </div>
@endsection
