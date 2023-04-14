@extends('layouts.admin')

@section('content')
  <div class="container">
    {{ Breadcrumbs::render('admin.shop') }}
    <div class="row">
      <div class="col-12">
        <div style="margin-bottom: 20px">
          <a href="{{route('admin.shop.create')}}" class="btn btn-lg btn-success">店舗新規登録</a>
        </div>
        @if(count($shops) > 0)
          @foreach($shops as $shop)
            <table class="table table-striped table-bordered">
              <tbody>
              <tr>
                <td><a href="{{route('admin.shop.edit', $shop->id)}}">{{$shop->name}}</a></td>
              </tr>
              <tr>
                <td>〒{{$shop->postal_code}} {{$shop->view_address}}</td>
              </tr>
              <tr>
                <td>📞 : {{$shop->phone_number}}</td>
              </tr>
              <tr>
                <td>URL : {!! $shop->view_url !!}</td>
              </tr>
              <tr>
                <td>
                  <form method="POST" action="{{route('admin.shop.delete', $shop->id)}}" accept-charset="UTF-8">
                    @csrf
                    @method('DELETE')
                    <input
                        class="btn btn-danger btn-sm"
                        type="submit"
                        value="削除する"
                        onclick="return confirm('店舗名「{{$shop->name}}」削除します。よろしいですか？')">
                  </form>
                </td>
              </tr>
              </tbody>
            </table>
          @endforeach
        @else
          <div class="alert alert-warning" role="alert">
            店舗は現在登録されておりません。<a href="{{route('admin.shop.create')}}">店舗登録する</a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
