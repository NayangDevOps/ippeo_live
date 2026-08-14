<article class="product-card" data-id="{{ $product->id }}">
  @if($product->discount)
    <span class="badge-off">{{ $product->discount }}% off</span>
  @endif
  <a class="product-media" href="{{ route('product.show', $product->slug) }}">
    <img src="{{ media_url($product->primaryImage()) }}" alt="{{ $product->name }}" loading="lazy" />
  </a>
  <div class="product-info">
    <h3><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
    <div class="rating">
      <span class="stars">{{ str_repeat('★', (int) round($product->rating)) }}{{ str_repeat('☆', 5 - (int) round($product->rating)) }}</span>
      <strong>{{ number_format($product->rating, 1) }}</strong>
      <span class="reviews">({{ $product->reviews_count }})</span>
    </div>
    <div class="price-row">
      <span class="price">₹{{ number_format($product->price, 0) }}</span>
      @if($product->mrp)
        <span class="mrp">₹{{ number_format($product->mrp, 0) }}</span>
      @endif
      @if($product->discount)
        <span class="save">{{ $product->discount }}% OFF</span>
      @endif
    </div>
    <div class="tags">
      @if($product->cashback)<span class="tag cashback">5% cashback</span>@endif
      @if($product->is_new || $product->badge === 'New Arrival')<span class="tag new">New Arrival</span>@endif
      @if($product->is_best_seller || $product->badge === 'Best Seller')<span class="tag best">Best Seller</span>@endif
    </div>
    <div class="product-actions">
      <button class="btn btn-cart" type="button"
        data-add="{{ $product->id }}"
        data-name="{{ $product->name }}"
        data-price="{{ $product->price }}"
        data-image="{{ media_url($product->primaryImage()) }}">
        Add to Cart
      </button>
      @if($product->amazon_url)
        <a class="btn btn-amazon" href="{{ $product->amazon_url }}" target="_blank" rel="noopener noreferrer">
          <span class="amz">a</span> Buy on Amazon
        </a>
      @endif
    </div>
  </div>
</article>
