@extends('layouts.store')
@section('title', 'New Launches | Ippeo')
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>New Launches</span></nav>
<h1>New Launches</h1>
<p>Fresh from nature — explore our newest botanical essentials.</p>
</div></section>
<section class="page-section"><div class="container">
<div class="product-grid">
@forelse($products as $product)
@include('partials.product-card', ['product' => $product])
@empty
<p class="empty-state">New launches coming soon.</p>
@endforelse
</div>
<div style="margin-top:1.5rem">{{ $products->links() }}</div>
</div></section>
@endsection
