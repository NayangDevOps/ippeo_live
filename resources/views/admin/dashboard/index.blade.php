@extends('admin.layouts.app')
@section('title','Dashboard')
@section('heading','Dashboard')
@section('content')
<div class="grid">
  <div class="card stat"><h3>{{ $products }}</h3><p>Products</p></div>
  <div class="card stat"><h3>{{ $orders }}</h3><p>Orders</p></div>
  <div class="card stat"><h3>{{ $customers }}</h3><p>Customers</p></div>
  <div class="card stat"><h3>{{ $enquiries }}</h3><p>Enquiries</p></div>
</div>
<div class="row2">
  <div class="card">
    <h3 style="margin-top:0">Recent Orders</h3>
    <table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($recentOrders as $o)
      <tr><td><a href="{{ route('admin.orders.show',$o) }}">{{ $o->order_number }}</a></td><td>{{ $o->customer_name }}</td><td>₹{{ number_format($o->total,0) }}</td><td>{{ ucfirst($o->status) }}</td></tr>
    @endforeach
    </tbody></table>
  </div>
  <div class="card">
    <h3 style="margin-top:0">Recent Enquiries</h3>
    <table><thead><tr><th>Name</th><th>Email</th><th>Date</th></tr></thead>
    <tbody>
    @foreach($recentEnquiries as $e)
      <tr><td><a href="{{ route('admin.enquiries.show',$e) }}">{{ $e->name }}</a></td><td>{{ $e->email }}</td><td>{{ $e->created_at->format('d M Y') }}</td></tr>
    @endforeach
    </tbody></table>
  </div>
</div>
@endsection
