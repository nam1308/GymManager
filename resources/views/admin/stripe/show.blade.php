@extends('layouts.admin')
@push('css')
  <style>
      .MyCardElement {
          height: 40px;
          padding: 10px 12px;
          width: 100%;
          color: #32325d;
          background-color: white;
          border: 1px solid transparent;
          border-radius: 4px;

          box-shadow: 0 1px 3px 0 #e6ebf1;
          -webkit-transition: box-shadow 150ms ease;
          transition: box-shadow 150ms ease;
      }

      .MyCardElement--focus {
          box-shadow: 0 1px 3px 0 #cfd7df;
      }

      .MyCardElement--invalid {
          border-color: #fa755a;
      }

      .MyCardElement--webkit-autofill {
          background-color: #fefde5 !important;
      }
  </style>
@endpush
@push('javascript-head')
@endpush
@section('content')
  <div class="container" style="margin-bottom: 100px;">
    {{ Breadcrumbs::render('admin.purchase.show', $product) }}
    <div class="card" style="margin-bottom: 20px;">
      <div class="card-body">
        <h1>{{$product['name']}}</h1>
        <p>{!! $product['description'] !!} </p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form action="{{route('admin.purchase.store', $product_id)}}" method="post" id="payment-form">
          @csrf
          <label>お名前</label>
          <input
              type="text"
              class="form-control form-control-lg"
              id="card-holder-name" required>
          <label>カード番号</label>
          <div class="form-group MyCardElement" id="card-element"></div>
          <div id="card-errors" role="alert" style='color:red'></div>
          <label>クーポンコード（お持ちの場合）</label>
          <input type="text" class="form-control form-control-lg" id="coupon" name="coupon">
          <div style="margin-top: 20px">
            <button
                class="btn btn-primary btn-block btn-lg"
                onclick="return confirm('購入しますか？')"
                id="card-button"
                data-secret="{{ $intent->client_secret }}">購入する
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('javascript-footer')
  <script type="module" src="https://js.stripe.com/v3/"></script>
  <script type="module">
		// HTMLの読み込み完了後に実行するようにする
		window.onload = init;

		function init() {

			// Configに設定したStripeのAPIキーを読み込む
			const stripe = Stripe("{{ config('app.stripe.pb_key') }}");
			const elements = stripe.elements();

			const style = {
				base: {
					color: "#32325d",
					fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
					fontSmoothing: "antialiased",
					fontSize: "16px",
					"::placeholder": {
						color: "#aab7c4"
					}
				},
				invalid: {
					color: "#fa755a",
					iconColor: "#fa755a"
				}
			};

			const cardElement = elements.create('card', {style: style, hidePostalCode: true});
			cardElement.mount('#card-element');

			const cardHolderName = document.getElementById('card-holder-name');
			const cardButton = document.getElementById('card-button');
			const clientSecret = cardButton.dataset.secret;
			const coupon = document.getElementById('coupon');

			cardButton.addEventListener('click', async (e) => {
				// formのsubmitボタンのデフォルト動作を無効にする
				e.preventDefault();
				const {setupIntent, error} = await stripe.confirmCardSetup(
					clientSecret, {
						payment_method: {
							card: cardElement,
							billing_details: {name: cardHolderName.value}
						}
					}
				);

				if (error) {
					// エラー処理
					console.log('error');
					alert(error);
				} else {
					// 問題なければ、stripePaymentHandlerへ
					stripePaymentHandler(setupIntent);
				}
			});
		}

		/**
		 *
		 * @param setupIntent
		 */
		function stripePaymentHandler(setupIntent) {
			const form = document.getElementById('payment-form');
			const hiddenInput = document.createElement('input');
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', 'stripePaymentMethod');
			hiddenInput.setAttribute('value', setupIntent.payment_method);
			form.appendChild(hiddenInput);
			// フォームを送信
			form.submit();
		}
  </script>
@endpush
