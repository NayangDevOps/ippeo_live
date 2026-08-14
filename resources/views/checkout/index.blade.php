@extends('layouts.store')
@section('title', 'Checkout | Ippeo')
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <a href="{{ route('cart') }}">Cart</a> <span>&gt;</span> <span>Checkout</span></nav>
<h1>Checkout</h1>
<p>Enter your details to place your order. Your customer account is created automatically with this order.</p>
</div></section>
<section class="page-section"><div class="container checkout-grid">
<form class="content-card form-stack" id="checkoutForm" method="post" action="{{ route('checkout.place') }}">
@csrf
<div id="cartItemsInputs"></div>
<div id="checkoutAlert" class="flash error" style="display:none;margin-bottom:1rem"></div>
<h3 style="margin-top:0;color:var(--green)">Your Details</h3>
<div class="form-row-2">
<div><label for="name">Full Name</label><input id="name" name="name" required /></div>
<div><label for="phone">Phone</label><input id="phone" name="phone" type="tel" required /></div>
</div>
<label for="email">Email</label><input id="email" name="email" type="email" required />
<label for="address">Address</label><textarea id="address" name="address" rows="3" required></textarea>
<div class="form-row-2">
<div><label for="city">City</label><input id="city" name="city" required /></div>
<div><label for="pincode">Pincode</label><input id="pincode" name="pincode" required /></div>
</div>
<label for="state">State</label><input id="state" name="state" required />
<h3 style="color:var(--green)">Payment Method</h3>
@if($codEnabled)
<label><input type="radio" name="payment_method" value="cod" @checked(true) /> Cash on Delivery</label>
@endif
@if($razorpayEnabled)
<label><input type="radio" name="payment_method" value="razorpay" @checked(!$codEnabled) /> Pay Online (UPI / Card / Netbanking)</label>
@endif
@if(!$codEnabled && !$razorpayEnabled)
<p style="color:#c0392b">Online checkout is temporarily unavailable. Please contact us to place an order.</p>
@endif
<button class="btn btn-primary btn-wide" type="submit" id="placeOrderBtn" @disabled(!$codEnabled && !$razorpayEnabled)>
<span class="btn-label">Place Order</span>
</button>
</form>
<aside class="content-card" id="checkoutSummary"></aside>
</div></section>
@endsection
@push('scripts')
@if($razorpayEnabled)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', () => {
  IppeoCart.renderCheckout({
    freeShippingMin: {{ (float) $freeShippingMin }},
    shippingFee: {{ (float) $shippingFee }},
  });

  const form = document.getElementById('checkoutForm');
  const alertBox = document.getElementById('checkoutAlert');
  const btn = document.getElementById('placeOrderBtn');
  if (!form) return;

  function showError(msg) {
    if (!alertBox) return;
    alertBox.style.display = 'block';
    alertBox.textContent = msg;
  }

  function setLoading(on) {
    if (!btn) return;
    btn.disabled = on;
    const label = btn.querySelector('.btn-label');
    if (label) label.textContent = on ? 'Processing…' : 'Place Order';
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    showError('');
    alertBox.style.display = 'none';
    setLoading(true);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: new FormData(form),
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok || !data.ok) {
        const msg = data.message
          || (data.errors ? Object.values(data.errors).flat().join(' ') : null)
          || 'Unable to place order. Please check your details.';
        showError(msg);
        setLoading(false);
        return;
      }

      if (data.razorpay) {
        const options = {
          key: data.key,
          amount: data.amount,
          currency: data.currency,
          name: data.name,
          description: data.description,
          order_id: data.order_id,
          prefill: data.prefill,
          theme: data.theme,
          handler: async function (response) {
            try {
              const verifyRes = await fetch(data.verify_url, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                  'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                  razorpay_order_id: response.razorpay_order_id,
                  razorpay_payment_id: response.razorpay_payment_id,
                  razorpay_signature: response.razorpay_signature,
                  order_number: data.order_number,
                }),
              });
              const verifyData = await verifyRes.json().catch(() => ({}));
              if (!verifyRes.ok || !verifyData.ok) {
                showError(verifyData.message || 'Payment verification failed. Contact support with your order number.');
                setLoading(false);
                return;
              }
              IppeoCart.clear();
              window.location.href = verifyData.redirect || data.success_url;
            } catch (err) {
              showError('Payment received but verification failed. Please contact support.');
              setLoading(false);
            }
          },
          modal: {
            ondismiss: function () {
              showError('Payment was cancelled. Your order is saved as awaiting payment — you can try again or contact us.');
              setLoading(false);
            }
          }
        };
        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (resp) {
          showError(resp.error?.description || 'Payment failed. Please try again.');
          setLoading(false);
        });
        rzp.open();
        return;
      }

      IppeoCart.clear();
      window.location.href = data.redirect;
    } catch (err) {
      showError('Network error. Please try again.');
      setLoading(false);
    }
  });
});
</script>
@endpush
