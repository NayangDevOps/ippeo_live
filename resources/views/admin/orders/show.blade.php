@extends('admin.layouts.app')
@section('title','Order '.$order->order_number)
@section('heading','Order '.$order->order_number)
@section('content')
<div class="row2">
<div class="card">
<h3 style="margin-top:0">Customer</h3>
<p>{{ $order->customer_name }}<br>{{ $order->customer_email }}<br>{{ $order->customer_phone }}</p>
<p>{{ $order->shipping_address }}<br>{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
<p><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} · {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}</p>
@if($order->razorpay_payment_id)
<p><strong>Razorpay Payment:</strong> {{ $order->razorpay_payment_id }}</p>
@endif
@if($order->paid_at)
<p><strong>Paid at:</strong> {{ $order->paid_at }}</p>
@endif
<p><strong>Confirmation email:</strong> {{ $order->confirmation_emailed ? 'Sent' : 'Not sent' }}</p>
<form method="post" action="{{ route('admin.orders.update',$order) }}">@csrf @method('PUT')
<label>Status</label>
<select name="status">
@foreach(['pending','confirmed','shipped','delivered','cancelled'] as $s)
<option value="{{ $s }}" @selected($order->status===$s)>{{ ucfirst($s) }}</option>
@endforeach
</select>
<label>Notes</label><textarea name="notes" rows="3">{{ $order->notes }}</textarea>
<button class="btn" style="margin-top:.8rem">Update Order</button>
</form>
</div>
<div class="card">
<h3 style="margin-top:0">Items</h3>
<table><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
<tbody>
@foreach($order->items as $item)
<tr><td>{{ $item->product_name }}</td><td>{{ $item->qty }}</td><td>₹{{ number_format($item->price,0) }}</td><td>₹{{ number_format($item->total,0) }}</td></tr>
@endforeach
</tbody></table>
<p>Subtotal: ₹{{ number_format($order->subtotal,0) }} · Shipping: ₹{{ number_format($order->shipping,0) }} · <strong>Total: ₹{{ number_format($order->total,0) }}</strong></p>
</div>
</div>
@endsection
