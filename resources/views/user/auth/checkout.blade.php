<p>決済ページへリダイレクトします。</p> 

{{-- stripeを読み込む --}}
<script src="https://js.stripe.com/v3/"></script> 

<script>
    const publicKey = '{{ $publicKey }}' 
    const stripe = Stripe(publicKey) 

    // window.onload ...画面を開いたら実行される
    window.onload = function() { 
        stripe.redirectToCheckout({ 
            sessionId: '{{ $session->id }}' 
            }).then(function (result) { // もし違った場合は...
                window.location.href = '{{ route('user.cart.index') }}'; 
                }); 
    }

</script>