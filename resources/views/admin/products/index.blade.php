@extends('admin.layouts.app')
@section('title','Products')
@section('heading','Products')
@section('actions')<a class="btn" href="{{ route('admin.products.create') }}">+ Add Product</a>@endsection
@section('content')
<div class="card">
<table>
<thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th></th></tr></thead>
<tbody>
@foreach($products as $p)
<tr>
<td><img src="{{ media_url($p->primaryImage()) }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px"></td>
<td>{{ $p->name }}</td>
<td>{{ $p->category->name ?? '-' }}</td>
<td>₹{{ number_format($p->price,0) }}</td>
<td>{{ $p->is_active ? 'Active' : 'Hidden' }}</td>
<td style="white-space:nowrap">
  <a class="btn sec" href="{{ route('admin.products.edit',$p) }}">Edit</a>
  <form action="{{ route('admin.products.destroy',$p) }}" method="post" style="display:inline" onsubmit="return confirm('Delete product?')">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
</td>
</tr>
@endforeach
</tbody></table>
{{ $products->links() }}
</div>
@endsection
