@extends('layouts.store')
@section('title', 'Categories | Ippeo')
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>Categories</span></nav>
<h1>More Categories</h1>
</div></section>
<section class="page-section"><div class="container">
<div class="cat-grid">
@foreach($categories as $cat)
<a class="cat-tile" href="{{ route('shop', ['category' => $cat->slug]) }}">
<span class="cat-icon">{{ $cat->icon ?: strtoupper(substr($cat->name,0,2)) }}</span>
<strong>{{ $cat->name }}</strong>
</a>
@endforeach
</div></div></section>
@endsection
