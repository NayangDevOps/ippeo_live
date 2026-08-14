@extends('layouts.store')
@section('title', $product->name . ' | Ippeo')
@section('content')
<section class="page-section">
  <div class="container">
    <nav class="breadcrumbs">
      <a href="{{ route('home') }}">Home</a> <span>&gt;</span>
      <a href="{{ route('shop') }}">Shop</a> <span>&gt;</span>
      @if($product->category)
        <a href="{{ route('shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a> <span>&gt;</span>
      @endif
      <span>{{ $product->name }}</span>
    </nav>

    <div class="pdp">
      <div>
        <div class="pdp-gallery">
          <img id="mainImage" src="{{ media_url($product->primaryImage()) }}" alt="{{ $product->name }}" />
        </div>
        @if($product->images->count())
          <div class="thumb-row" style="display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap">
            @foreach($product->images as $img)
              <button type="button" class="thumb-btn" data-src="{{ media_url($img->path) }}" style="border:1px solid var(--border);border-radius:8px;padding:0;background:#fff;cursor:pointer;width:72px;height:72px;overflow:hidden">
                <img src="{{ media_url($img->path) }}" alt="" style="width:100%;height:100%;object-fit:cover" />
              </button>
            @endforeach
          </div>
        @endif
        @if($product->videos->count())
          <div class="pdp-videos" style="margin-top:1.25rem">
            <h3 style="margin:0 0 .75rem;color:var(--green)">Product Videos</h3>
            @foreach($product->videos as $video)
              <video controls playsinline preload="metadata" style="width:100%;border-radius:10px;background:#000;margin-bottom:.75rem"
                @if($video->poster) poster="{{ media_url($video->poster) }}" @endif>
                <source src="{{ media_url($video->path) }}" type="video/mp4">
              </video>
            @endforeach
          </div>
        @endif
      </div>

      <div class="pdp-meta">
        <p class="sku">SKU: {{ $product->sku }} @if($product->size)· Size: {{ $product->size }}@endif</p>
        <h1>{{ $product->name }}</h1>
        <div class="rating">
          <span class="stars">{{ str_repeat('★', (int) round($product->rating)) }}{{ str_repeat('☆', 5 - (int) round($product->rating)) }}</span>
          <strong>{{ number_format($product->rating, 1) }}</strong>
          <span class="reviews">({{ $product->reviews_count }} reviews)</span>
        </div>
        <div class="price-row" style="margin-top:.75rem">
          <span class="price">₹{{ number_format($product->price, 0) }}</span>
          @if($product->mrp)<span class="mrp">₹{{ number_format($product->mrp, 0) }}</span>@endif
          @if($product->discount)<span class="save">{{ $product->discount }}% OFF</span>@endif
        </div>
        <p style="color:var(--muted);margin:1rem 0">{{ $product->description }}</p>
        <div class="qty-row">
          <span>Quantity</span>
          <div class="qty-control">
            <button type="button" id="qtyMinus">−</button>
            <input id="qtyInput" value="1" readonly />
            <button type="button" id="qtyPlus">+</button>
          </div>
        </div>
        <div class="pdp-actions">
          <button class="btn btn-cart" type="button" id="addBtn"
            data-add="{{ $product->id }}"
            data-qty="1"
            data-name="{{ $product->name }}"
            data-price="{{ $product->price }}"
            data-image="{{ media_url($product->primaryImage()) }}">Add to Cart</button>
          @if($product->amazon_url)
            <a class="btn btn-amazon" href="{{ $product->amazon_url }}" target="_blank" rel="noopener noreferrer"><span class="amz">a</span> Buy on Amazon</a>
          @endif
        </div>
      </div>
    </div>

    <div class="pdp-tabs">
      <div class="tab-btns">
        <button type="button" class="is-active" data-tab="desc">Description</button>
        <button type="button" data-tab="benefits">Benefits</button>
        <button type="button" data-tab="ingredients">Ingredients</button>
        <button type="button" data-tab="howto">How to Use</button>
      </div>
      <div class="tab-panel is-active" id="tab-desc"><p>{{ $product->description }}</p></div>
      <div class="tab-panel" id="tab-benefits"><ul>@foreach($product->benefits_list as $b)<li>{{ $b }}</li>@endforeach</ul></div>
      <div class="tab-panel" id="tab-ingredients"><p>{{ $product->ingredients }}</p></div>
      <div class="tab-panel" id="tab-howto"><p>{{ $product->how_to_use }}</p></div>
    </div>

    @if($related->count())
      <h2 style="margin-top:2.5rem">You may also like</h2>
      <div class="product-grid" style="margin-top:1rem">
        @foreach($related as $item)
          @include('partials.product-card', ['product' => $item])
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  let qty = 1;
  const input = document.getElementById('qtyInput');
  const addBtn = document.getElementById('addBtn');
  const syncQty = () => {
    input.value = qty;
    if (addBtn) addBtn.dataset.qty = String(qty);
  };
  document.getElementById('qtyMinus').onclick = () => { qty = Math.max(1, qty - 1); syncQty(); };
  document.getElementById('qtyPlus').onclick = () => { qty += 1; syncQty(); };
  document.querySelectorAll('.thumb-btn').forEach(btn => {
    btn.onclick = () => { document.getElementById('mainImage').src = btn.dataset.src; };
  });
  document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.onclick = () => {
      document.querySelectorAll('[data-tab]').forEach(b => b.classList.remove('is-active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('is-active'));
      btn.classList.add('is-active');
      document.getElementById('tab-' + btn.dataset.tab).classList.add('is-active');
    };
  });
});
</script>
@endpush
