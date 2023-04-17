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
        {{ Breadcrumbs::render('admin.pay.edit', $accountInfor) }}
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pay.update',$accountInfor['pm_id']) }}" method="post" id="payment-form">
{{--                <form action="" method="post" id="payment-form">--}}
                    @csrf
                    <label>お名前</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="card-holder-name" value="{{ $accountInfor['name'] }}" required>
                    <label>カード番号</label>
                    <div class="form-group MyCardElement" id="card-element"></div>
                    <div id="card-errors" role="alert" style='color:red'></div>
                    <div style="margin-top: 20px">
                        <button
                            class="btn btn-primary btn-block btn-lg"
                            onclick="return confirm('購入しますか？')"
                            id="card-button"
                            data-secret="{{ $intent->client_secret }}">アップデート
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
        window.onload = init;

        function init() {
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

            cardButton.addEventListener('click', async (e) => {
                var cardElementtest = elements.getElement('card');
                console.log('cardElementtest',cardElementtest);

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
                    console.log('error',error);
                    alert(error);
                } else {
                    stripePaymentHandler(setupIntent);
                }
            });
        }

        function stripePaymentHandler(setupIntent) {
            const form = document.getElementById('payment-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripePaymentMethod');
            hiddenInput.setAttribute('value', setupIntent.payment_method);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
@endpush

