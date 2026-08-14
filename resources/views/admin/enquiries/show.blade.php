@extends('admin.layouts.app')
@section('title','Enquiry')
@section('heading','Enquiry from '.$enquiry->name)
@section('content')
<div class="card">
<p><strong>Email:</strong> {{ $enquiry->email }}</p>
<p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
<p><strong>Source:</strong> {{ $enquiry->source }}</p>
<p><strong>Emailed to info:</strong> {{ $enquiry->emailed ? 'Yes' : 'No' }}</p>
<p style="white-space:pre-wrap;background:#f5f6f7;padding:1rem;border-radius:8px">{{ $enquiry->message }}</p>
</div>
@endsection
