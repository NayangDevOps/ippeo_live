@extends('admin.layouts.app')
@section('title','Banners') @section('heading','Homepage Banners')
@section('actions')<a class="btn" href="{{ route('admin.banners.create') }}">+ Add Banner</a>@endsection
@section('content')
<div class="card"><table>
<thead><tr><th>Image</th><th>Title</th><th>Order</th><th>Active</th><th></th></tr></thead>
<tbody>
@foreach($banners as $b)
<tr>
<td>@if($b->image)<img src="{{ media_url($b->image) }}" style="width:90px;height:48px;object-fit:cover;border-radius:6px">@endif</td>
<td>{{ $b->title }}</td><td>{{ $b->sort_order }}</td><td>{{ $b->is_active?'Yes':'No' }}</td>
<td><a class="btn sec" href="{{ route('admin.banners.edit',$b) }}">Edit</a>
<form style="display:inline" method="post" action="{{ route('admin.banners.destroy',$b) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form></td>
</tr>
@endforeach
</tbody></table>{{ $banners->links() }}</div>
@endsection
