@php
    use Carbon\Carbon;
@endphp
@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.basic-setting') }}
    @if (session()->has('flash_message'))
        <div class="alert alert-success">{{session()->get('flash_message')}}</div>
    @endif
    {{ $vendors->render() }}
    @if(count($vendors) > 0)
      <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
          <th>会社名</th>
          <th>代表者</th>
          <th>電話番号</th>
          <th>住所</th>
          <th>申込日</th>
          <th>初期費用</th>
          <th>無料期間</th>
          <th>直近の支払い日</th>
          <th>料金プラン</th>
          <th>金額</th>
          <th>メールアドレス</th>
          <th>備考</th>
          <th>ブロック</th>
          <th>登録日</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($vendors as $vendor)
          <tr>
            <td><a href="{{ route('super-admin.basic-setting.show', $vendor->vendor_id) }}"> {{ $vendor->basicSetting->company_name ?? '' }} </a></td>
            <td><a href="{{ route('super-admin.trainer.show', $vendor->id) }}">{{ $vendor->name }}</a></td>
            <td>{{ $vendor->basicSetting->phone_number ?? '' }}</a></td>
            <td>〒{{ $vendor->basicSetting->postal_code ?? '' }} {{ $vendor->basicSetting->view_address ?? '' }}</td>
            {{-- TODO: render data backend here --}}
            <td>{{ $vendor->apply->created_at ?? '' }}</td>
            <td>{{ $vendor->product->initial_price ?? ''}}</td>
            <td>{{ $vendor->product->free_term ?? ''}}</td>
            <td>
                @php
                    $product = $vendor->product;
                    if ($product && $product->created_at){
                        $trialTime = $product->created_at->addDay($product->free_term + 1)->startOfDay();
                        $isPast = $trialTime->isPast();
                        if ($isPast){
                            echo $product->reference_date->addMonth()->addDay()->startOfDay() ?? '';
                        }
                        else{
                            echo $trialTime;
                        }
                    }
                @endphp
            </td>
            <td>
                {{$product->productCategory->type ?? '' }}
            </td>
            <td>
                {{$product->productCategory->price ?? ''}}
            </td>
            <td>
                {{$vendor->email ?? ''}}
            </td>
            <td>
                {{$vendor->basicSetting->note_super_admin ?? ''}}
            </td>
            <td>
                {{$vendor->block ? 'ON' :'OFF'}}
            </td>
            <td>{{ $vendor->lineMessage->created_at ?? '' }}</td>

          </tr>
        @endforeach
        </tbody>
      </table>
    @else
      <div class="alert alert-warning" role="alert">
        データーはありません。
      </div>
    @endif
    {{ $vendors->render() }}
  </div>
@endsection
