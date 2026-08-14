@extends('admin.layouts.app')
@section('title','Categories') @section('heading','Categories')
@section('actions')<a class="btn" href="{{ route('admin.categories.create') }}">+ Add Category</a>@endsection
@section('content')
<div class="card"><table>
<thead><tr><th>Icon</th><th>Name</th><th>Order</th><th>Active</th><th></th></tr></thead>
<tbody>
@foreach($categories as $c)
<tr>
<td>{{ $c->icon }}</td><td>{{ $c->name }}</td><td>{{ $c->sort_order }}</td><td>{{ $c->is_active?'Yes':'No' }}</td>
<td>
<a class="btn sec" href="{{ route('admin.categories.edit',$c) }}">Edit</a>
<form style="display:inline" method="post" action="{{ route('admin.categories.destroy',$c) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form>
</td></tr>
@endforeach
</tbody></table>{{ $categories->links() }}</div>
@endsection
