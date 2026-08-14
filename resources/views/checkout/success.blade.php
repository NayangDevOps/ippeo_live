@extends('layouts.store')
@section('title', 'Order Confirmed | Ippeo')
@section('content')
<section class="page-section"><div class="container">
<div class="content-card empty-state" style="max-width:560px;margin:0 auto">
<h1 style="color:var(--green);font-family:var(--serif)">Thank You!</h1>
<p>Order <strong>#{{ $order->order_number }}</strong> has been placed successfully.</p>
<p>We've saved your details for this order. Total: <strong>₹{{ number_format($order->total, 0) }}</strong></p>
<p>Payment: <strong>{{ strtoupper($order->payment_method) }}</strong>
@if($order->payment_status)
 — {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
@endif
</p>
<p>A confirmation email will be sent to <strong>{{ $order->customer_email }}</strong> if email notifications are enabled.</p>
<a class="btn btn-primary" href="{{ route('shop') }}">Continue Shopping</a>
</div></div></section>
@endsection
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => IppeoCart.clear());</script>
@endpush
