@extends('admin.layouts.app')
@section('title',$customer->name)
@section('heading',$customer->name)
@section('content')
<div class="card">
<p>{{ $customer->email }} · {{ $customer->phone }}</p>
<p>{{ $customer->address }}<br>{{ $customer->city }}, {{ $customer->state }} - {{ $customer->pincode }}</p>
<h3>Orders</h3>
<table><thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
@foreach($customer->orders as $o)
<tr><td><a href="{{ route('admin.orders.show',$o) }}">{{ $o->order_number }}</a></td><td>₹{{ number_format($o->total,0) }}</td><td>{{ ucfirst($o->status) }}</td><td>{{ $o->created_at->format('d M Y') }}</td></tr>
@endforeach
</tbody></table>
</div>
@endsection
