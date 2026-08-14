@extends('admin.layouts.app')
@section('title','Enquiries') @section('heading','Enquiries')
@section('content')
<div class="card"><table>
<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Emailed</th><th>Date</th><th></th></tr></thead>
<tbody>
@foreach($enquiries as $e)
<tr>
<td><a href="{{ route('admin.enquiries.show',$e) }}">{{ $e->name }}</a></td>
<td>{{ $e->email }}</td><td>{{ $e->phone }}</td>
<td>{{ $e->emailed?'Yes':'No' }}</td>
<td>{{ $e->created_at->format('d M Y H:i') }}</td>
<td><form method="post" action="{{ route('admin.enquiries.destroy',$e) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form></td>
</tr>
@endforeach
</tbody></table>{{ $enquiries->links() }}</div>
@endsection
