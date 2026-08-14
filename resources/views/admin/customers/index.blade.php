@extends('admin.layouts.app')
@section('title','Customers') @section('heading','Customers')
@section('content')
<div class="card"><table>
<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th></th></tr></thead>
<tbody>
@foreach($customers as $c)
<tr>
<td>{{ $c->name }}</td><td>{{ $c->email }}</td><td>{{ $c->phone }}</td><td>{{ $c->orders_count }}</td>
<td><a class="btn sec" href="{{ route('admin.customers.show',$c) }}">View</a></td>
</tr>
@endforeach
</tbody></table>{{ $customers->links() }}</div>
@endsection
