@extends('admin.layouts.app')
@section('title','CMS Pages') @section('heading','CMS Pages')
@section('actions')<a class="btn" href="{{ route('admin.pages.create') }}">+ Add Page</a>@endsection
@section('content')
<div class="card"><table>
<thead><tr><th>Title</th><th>Slug</th><th>Active</th><th></th></tr></thead>
<tbody>
@foreach($pages as $p)
<tr><td>{{ $p->title }}</td><td>/page/{{ $p->slug }}</td><td>{{ $p->is_active?'Yes':'No' }}</td>
<td><a class="btn sec" href="{{ route('admin.pages.edit',$p) }}">Edit</a>
<form style="display:inline" method="post" action="{{ route('admin.pages.destroy',$p) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form></td></tr>
@endforeach
</tbody></table>{{ $pages->links() }}</div>
@endsection
