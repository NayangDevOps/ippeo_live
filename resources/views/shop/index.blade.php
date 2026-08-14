@extends('layouts.store')
@section('title', 'Shop | Ippeo Essential Products')
@section('content')
<section class="page-hero">
  <div class="container">
    <nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>Shop</span></nav>
    <h1>Our Products</h1>
    <p>Nature-inspired essentials for healthy, glowing skin.</p>
  </div>
</section>
<section class="page-section">
  <div class="container shop-layout">
    <aside class="content-card filter-box">
      <h3>Categories</h3>
      <ul class="filter-list">
        <li><a href="{{ route('shop') }}" class="{{ !request('category') ? 'is-active' : '' }}">All Products</a></li>
        @foreach($categories as $cat)
          <li><a href="{{ route('shop', ['category' => $cat->slug]) }}" class="{{ $activeCategory === $cat->slug ? 'is-active' : '' }}">{{ $cat->name }}</a></li>
        @endforeach
      </ul>
    </aside>
    <div>
      <div class="shop-toolbar"><p>{{ $products->total() }} products</p></div>
      <div class="product-grid">
        @forelse($products as $product)
          @include('partials.product-card', ['product' => $product])
        @empty
          <p class="empty-state">No products found.</p>
        @endforelse
      </div>
      <div style="margin-top:1.5rem">{{ $products->links() }}</div>
    </div>
  </div>
</section>
@endsection
