@extends('layouts.admin')

@section('content')
    <div class="container">
        {{ Breadcrumbs::render('admin.pay') }}
        <div class="row">
            <div class="col-12">
                @if($admin->stripe_id)
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th class="w-25">名義 : </th>
                                <td>{{ $accountInfor['name'] }}</td>
                            </tr>
                            <tr>
                                <th class="w-25">ナンバー : </th>
                                <td><b>**** **** ****</b> {{ $accountInfor['last4'] }}</td>
                            </tr>
                            <tr>
                                <th class="w-25">有効期限 : </th>
                                <td>
                                    {{ $accountInfor['month'] }}
                                    /
                                    {{ $accountInfor['year'][-2].$accountInfor['year'][-1] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('admin.pay.edit', $accountInfor['pm_id']) }}" class="btn btn-success btn-lg">編集する</a>
                @else
                    <a href="{{ route('admin.pay.create') }}" class="btn btn-success btn-lg">決済情報入力</a>
                @endif
            </div>
        </div>
    </div>
@endsection
