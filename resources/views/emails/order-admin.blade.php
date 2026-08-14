<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f3f5f4;font-family:Arial,Helvetica,sans-serif;color:#1a1a1a">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f5f4;padding:24px 12px">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e8eb">
<tr><td style="background:#12351a;color:#fff;padding:20px 24px">
  <h1 style="margin:0;font-size:22px">New Order Received</h1>
  <p style="margin:8px 0 0;opacity:.9">#{{ $order->order_number }}</p>
</td></tr>
<tr><td style="padding:24px">
  <p style="margin-top:0"><strong>Customer:</strong> {{ $order->customer_name }}</p>
  <p style="margin:4px 0"><strong>Email:</strong> {{ $order->customer_email }}</p>
  <p style="margin:4px 0"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
  <p style="margin:4px 0"><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
  <p style="margin:4px 0"><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} / {{ ucfirst($order->payment_status) }}</p>
  @if($order->razorpay_payment_id)
  <p style="margin:4px 0"><strong>Razorpay Payment ID:</strong> {{ $order->razorpay_payment_id }}</p>
  @endif

  <table width="100%" cellpadding="0" cellspacing="0" style="margin:16px 0;border:1px solid #e5e8eb;border-radius:8px">
    <tr style="background:#f7f9f8">
      <td style="padding:10px 12px;font-weight:bold">Item</td>
      <td style="padding:10px 12px;font-weight:bold" align="center">Qty</td>
      <td style="padding:10px 12px;font-weight:bold" align="right">Total</td>
    </tr>
    @foreach($order->items as $item)
    <tr>
      <td style="padding:10px 12px;border-top:1px solid #e5e8eb">{{ $item->product_name }}</td>
      <td style="padding:10px 12px;border-top:1px solid #e5e8eb" align="center">{{ $item->qty }}</td>
      <td style="padding:10px 12px;border-top:1px solid #e5e8eb" align="right">₹{{ number_format($item->total, 0) }}</td>
    </tr>
    @endforeach
  </table>

  <p style="margin:0"><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal, 0) }}</p>
  <p style="margin:4px 0"><strong>Shipping:</strong> {{ $order->shipping > 0 ? '₹'.number_format($order->shipping, 0) : 'FREE' }}</p>
  <p style="margin:4px 0;font-size:18px"><strong>Total:</strong> ₹{{ number_format($order->total, 0) }}</p>
  <p style="margin:16px 0 0;color:#5c6670;font-size:13px">Placed at {{ $order->created_at }}</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
