@extends('admin.layouts.app')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')
@section('heading', $product->exists ? 'Edit Product' : 'Add Product')
@section('content')
<form class="card" id="productForm" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}">
@csrf
@if($product->exists) @method('PUT') @endif
<div class="row2">
  <div>
    <label>Name</label><input name="name" value="{{ old('name',$product->name) }}" required />
    <label>Category</label>
    <select name="category_id"><option value="">—</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(old('category_id',$product->category_id)==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
    <label>SKU</label><input name="sku" value="{{ old('sku',$product->sku) }}" />
    <label>Size</label><input name="size" value="{{ old('size',$product->size) }}" />
    <label>Short description</label><input name="short_description" value="{{ old('short_description',$product->short_description) }}" />
    <label>Description</label><textarea name="description" rows="4">{{ old('description',$product->description) }}</textarea>
    <label>Benefits (one per line)</label><textarea name="benefits" rows="4">{{ old('benefits',$product->benefits) }}</textarea>
    <label>Ingredients</label><textarea name="ingredients" rows="3">{{ old('ingredients',$product->ingredients) }}</textarea>
    <label>How to use</label><textarea name="how_to_use" rows="3">{{ old('how_to_use',$product->how_to_use) }}</textarea>
  </div>
  <div>
    <div class="row2">
      <div><label>Price</label><input type="number" step="0.01" name="price" value="{{ old('price',$product->price) }}" required /></div>
      <div><label>MRP</label><input type="number" step="0.01" name="mrp" value="{{ old('mrp',$product->mrp) }}" /></div>
    </div>
    <div class="row2">
      <div><label>Discount %</label><input type="number" name="discount" value="{{ old('discount',$product->discount ?? 0) }}" /></div>
      <div><label>Stock</label><input type="number" name="stock" value="{{ old('stock',$product->stock ?? 100) }}" /></div>
    </div>
    <div class="row2">
      <div><label>Rating</label><input type="number" step="0.1" name="rating" value="{{ old('rating',$product->rating ?? 0) }}" /></div>
      <div><label>Reviews count</label><input type="number" name="reviews_count" value="{{ old('reviews_count',$product->reviews_count ?? 0) }}" /></div>
    </div>
    <label>Badge</label><input name="badge" value="{{ old('badge',$product->badge) }}" placeholder="New Arrival / Best Seller" />
    <label>Amazon URL</label><input name="amazon_url" value="{{ old('amazon_url',$product->amazon_url) }}" />
    <label>Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order',$product->sort_order ?? 0) }}" />
    <label>Thumbnail</label><input type="file" name="thumbnail" accept="image/*" />
    @if($product->thumbnail)<p><img src="{{ media_url($product->thumbnail) }}" style="height:70px;border-radius:8px"></p>@endif

    <label>Upload more product images (multiple)</label>
    <input type="file" name="images[]" accept="image/*" multiple />

    <label>Upload product videos (MP4/WebM, multiple, max 50MB each)</label>
    <input type="file" name="videos[]" accept="video/mp4,video/webm,video/quicktime" multiple />

    <div class="checks">
      <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->is_active ?? true))> Active</label>
      <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$product->is_featured))> Featured on Home</label>
      <label><input type="checkbox" name="is_new" value="1" @checked(old('is_new',$product->is_new))> New Arrival</label>
      <label><input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller',$product->is_best_seller))> Best Seller</label>
      <label><input type="checkbox" name="cashback" value="1" @checked(old('cashback',$product->cashback))> 5% cashback</label>
    </div>
  </div>
</div>
<button class="btn" type="submit" style="margin-top:1rem">Save Product</button>
</form>

@if($product->exists)
<div class="card" style="margin-top:1rem">
  <h3 style="margin-top:0;color:#226b2c">Existing Images</h3>
  <div class="media-grid">
    @forelse($product->images as $img)
      <figure>
        <img src="{{ media_url($img->path) }}" alt="">
        <form action="{{ route('admin.product-images.destroy',$img) }}" method="post">@csrf @method('DELETE')
          <button class="btn danger" style="width:100%;padding:.3rem;font-size:.75rem" type="submit">Remove</button>
        </form>
      </figure>
    @empty
      <p style="color:#5c6670">No gallery images yet.</p>
    @endforelse
  </div>
  <h3 style="color:#226b2c">Existing Videos</h3>
  <div class="media-grid">
    @forelse($product->videos as $vid)
      <figure style="width:180px">
        <video src="{{ media_url($vid->path) }}" muted></video>
        <form action="{{ route('admin.product-videos.destroy',$vid) }}" method="post">@csrf @method('DELETE')
          <button class="btn danger" style="width:100%;padding:.3rem;font-size:.75rem" type="submit">Remove</button>
        </form>
      </figure>
    @empty
      <p style="color:#5c6670">No videos yet. Upload MP4/WebM above and save.</p>
    @endforelse
  </div>
</div>
@endif
@endsection
