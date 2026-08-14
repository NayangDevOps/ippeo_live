@extends('admin.layouts.app')
@section('title','Orders') @section('heading','Orders')
@section('content')
<div class="card"><table>
<thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
@foreach($orders as $o)
<tr>
<td><a href="{{ route('admin.orders.show',$o) }}">{{ $o->order_number }}</a></td>
<td>{{ $o->customer_name }}<br><small>{{ $o->customer_phone }}</small></td>
<td>₹{{ number_format($o->total,0) }}</td>
<td>{{ strtoupper($o->payment_method) }}<br><small>{{ ucfirst(str_replace('_', ' ', $o->payment_status ?? 'pending')) }}</small></td>
<td>{{ ucfirst($o->status) }}</td>
<td>{{ $o->created_at->format('d M Y H:i') }}</td>
</tr>
@endforeach
</tbody></table>{{ $orders->links() }}</div>
@endsection
