@extends('layouts.super-admin')

@section('content')
  <div class="container-fluid">
    {{ Breadcrumbs::render('super-admin.basic-setting.show', $basic_setting) }}
    <div class="row">
      <div class="col-4">
        <table class="table table-striped table-bordered table-hover">
          <tbody>
          <tr>
            <th>会社名</th>
            <td>{{ $basic_setting->company_name }}</td>
          </tr>
          <tr>
            <th>代表者</th>
            <td><a href="{{route('super-admin.trainer.show', $basic_setting->admin->id)}}">{{ $basic_setting->admin->name }}</a></td>
          </tr>
          <tr>
            <th>電話番号</th>
            <td>{{ $basic_setting->phone_number }}</td>
          </tr>
          <tr>
            <th>住所</th>
            <td>〒{{$basic_setting->postal_code}} {{ $basic_setting->view_address}}</td>
          </tr>
          <tr>
            <th>申込日</th>
            <td>{{$basic_setting->created_at}}</td>
          </tr>
           <tr>
            <th>初期費用</th>
            <td>{{ $product->initial_price ?? '' }}</td>
          </tr>
           <tr>
            <th>無料期間</th>
            <td>{{ $product->free_term ?? ''}}</td>
          </tr>
           <tr>
            <th>直近の支払い日</th>
            <td>
                @php
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
          </tr>
           <tr>
            <th>料金プラン</th>
            <td>
                {{$product->productCategory->type ?? '' }}
            </td>
          </tr>
           <tr>
            <th>金額</th>
            <td>
                {{$product->productCategory->price ?? ''}}
            </td>
          </tr>
           <tr>
            <th>メールアドレス</th>
            <td>
                {{$admin->email ?? ''}}
            </td>
          </tr>
          <tr>
            <th>備考</th>
            <td>
                {{$basic_setting->note_super_admin ?? ''}}
            </td>
          </tr>
          <tr>
            <th>ブロック</th>
            <td>
                <label for="btn-block">
                    <input type="checkbox" id="btn-block" class="check-box-btn" @if ($basic_setting->admin->block)
                        {{'checked'}}
                    @endif>
                    <div class="btn-wrap">
                        <div class="btn-switch"></div>
                    </div>
                </label>
            </td>
          </tr>
          <tr>
            <th>登録日</th>
            <td>{{ $basic_setting->admin->lineMessage->created_at ?? '' }}</td>
          </tr>
          <tr>
            <th>更新日</th>
            <td>{{ $basic_setting->admin->lineMessage->updated_at ?? ''}}</td>
          </tr>
          <tr>
            <th colspan="2"><a id="btn-edit" href="{{route('super-admin.basic-setting.edit',['vendor_id'=>$basic_setting->vendor_id])}}">編集</a></th>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-8">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active"
               id="pills-home-tab"
               data-toggle="pill"
               href="#pills-home"
               role="tab"
               aria-controls="pills-home"
               aria-selected="true">トレーナー <span class="badge badge-light">{{count($trainers)}}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link"
               id="pills-profile-tab"
               data-toggle="pill"
               href="#pills-profile"
               role="tab"
               aria-controls="pills-profile"
               aria-selected="false">メニュー <span class="badge badge-light">{{count($courses)}}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link"
               id="pills-contact-tab"
               data-toggle="pill"
               href="#pills-contact"
               role="tab"
               aria-controls="pills-contact"
               aria-selected="false">店舗 <span class="badge badge-light">{{count($shops)}}</span></a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @if($trainers)
              <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                  <th>ログインID</th>
                  <th>名前</th>
                  <th>メールアドレス</th>
                  <th>システム権限</th>
                  <th>トレーナー権限</th>
                  <th>自己紹介</th>
                  <th>登録日</th>
                  <th>更新日</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($trainers as $trainer)
                  <tr>
                    <td> {{ $trainer->login_id }} </td>
                    <td><img src="{{$trainer->profileImage->getPhotoUrl()}}" alt="profile" width="30" class="rounded-circle">
                      <a href="{{ route('super-admin.trainer.show', $trainer->id) }}"> {{ $trainer->name }} </a>
                    </td>
                    <td> {{ $trainer->email }} </td>
                    <td>{{ $trainer->view_role}}</td>
                    <td>{{ $trainer->view_trainer_role}}</td>
                    <td>{{ $trainer->self_introduction}}</td>
                    <td>{{ $trainer->created_at }}</td>
                    <td>{{ $trainer->updated_at }}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            @else
              <div class="alert alert-warning" role="alert">
                データーが見つかりません
              </div>
            @endif
          </div>
          <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            @if($courses)
              <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                  <th>メニュー名</th>
                  <th>時間</th>
                  <th>価格</th>
                  <th>登録日</th>
                  <th>更新日</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                  <tr>
                    <td><a href="{{route('admin.course.edit', $course->id)}}">{{$course->name}}</a></td>
                    <td>{{$course->view_course_time}}</td>
                    <td>{{$course->view_price}}</td>
                    <td>{{$course->created_at}}</td>
                    <td>{{$course->updated_at}}</td>
                    <td>
                      <form method="POST" action="{{route('admin.course.delete', $course->id)}}" accept-charset="UTF-8">
                        @csrf
                        @method('DELETE')
                        <input
                            class="btn btn-danger btn-sm"
                            type="submit"
                            value="削除する"
                            onclick="return confirm('メニュー名「{{$course->name}}」削除します。よろしいですか？')">
                      </form>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            @else
              <div class="alert alert-warning" role="alert">
                データーが見つかりません
              </div>
            @endif
          </div>
          <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            @if($trainers)
              <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                  <th>店舗名</th>
                  <th>住所</th>
                  <th>電話番号</th>
                  <th>URL</th>
                  <th>登録日</th>
                  <th>更新日</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($shops as $shop)
                  <tr>
                    <td><a href="{{route('super-admin.shop.show', $shop->id)}}">{{$shop->name}}</a></td>

                    <td>〒{{$shop->postal_code}} {{$shop->view_address}}</td>
                    <td>{{$shop->phone_number}}</td>
                    <td>{!! $shop->view_url !!}</td>
                    <td>{{$shop->created_at}}</td>
                    <td>{{$shop->updated_at}}</td>
                    <td>
                      <form method="POST" action="{{route('super-admin.shop.destroy', $shop->id)}}" accept-charset="UTF-8">
                        @csrf
                        @method('DELETE')
                        <input
                            class="btn btn-danger btn-sm"
                            type="submit"
                            value="削除する"
                            onclick="return confirm('店舗名「{{$shop->name}}」削除します。よろしいですか？')">
                        <input type="hidden" value="{{$shop->vendor_id}}" name="vendor_id">
                      </form>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            @else
              <div class="alert alert-warning" role="alert">
                データーが見つかりません
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@push('css')
    <style>
        :root{
            --bg-active: #3cff3c;
            --bg-normal: #99a099;
        }
        .btn-wrap{
            width: 60px;
            height: 30px;
            border: none;
            border-radius: 20px;
            background-color: var(--bg-normal);
            display: flex;
            align-items: center;
            padding: 2px 5px;
            cursor: pointer;
            transform: translateX(0);
            transition: all 0.2s ease;
        }

        .btn-switch{
            width: 20px;
            height: 20px;
            background-color: #fff;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .check-box-btn{
            display: none;
        }

        .check-box-btn:checked + .btn-wrap{
            background-color: var(--bg-active);
        }

        .check-box-btn:checked + .btn-wrap .btn-switch{
            transform: translateX(30px);
        }
    </style>
@endpush

@push('scripts')
    <script>
       window.onload = function () {
            $('#btn-block').on('change',function (e) {
                const oldValue = e.target.checked
                console.log(oldValue);
                if(window.confirm(!oldValue ? 'この会社のブロックを解除しますか' : 'この会社をブロックしますか')){
                    e.target.checked = oldValue
                    $.ajax({
                        url: "{{route('super-admin.basic-setting.updateBlock',['vendor_id'=>$basic_setting->vendor_id])}}",
                        method: 'put',
                        data: {
                            _token: "{{csrf_token()}}",
                            block: oldValue ? 1 : 0
                        },
                        success: function (res) {
                            console.log(res);
                        }
                    })
                }
                else{
                    e.target.checked = !oldValue;
                }
            })
       }
    </script>
@endpush
