<article class="product-card" data-id="<?php echo e($product->id); ?>">
  <?php if($product->discount): ?>
    <span class="badge-off"><?php echo e($product->discount); ?>% off</span>
  <?php endif; ?>
  <a class="product-media" href="<?php echo e(route('product.show', $product->slug)); ?>">
    <img src="<?php echo e(media_url($product->primaryImage())); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" />
  </a>
  <div class="product-info">
    <h3><a href="<?php echo e(route('product.show', $product->slug)); ?>"><?php echo e($product->name); ?></a></h3>
    <div class="rating">
      <span class="stars"><?php echo e(str_repeat('★', (int) round($product->rating))); ?><?php echo e(str_repeat('☆', 5 - (int) round($product->rating))); ?></span>
      <strong><?php echo e(number_format($product->rating, 1)); ?></strong>
      <span class="reviews">(<?php echo e($product->reviews_count); ?>)</span>
    </div>
    <div class="price-row">
      <span class="price">₹<?php echo e(number_format($product->price, 0)); ?></span>
      <?php if($product->mrp): ?>
        <span class="mrp">₹<?php echo e(number_format($product->mrp, 0)); ?></span>
      <?php endif; ?>
      <?php if($product->discount): ?>
        <span class="save"><?php echo e($product->discount); ?>% OFF</span>
      <?php endif; ?>
    </div>
    <div class="tags">
      <?php if($product->cashback): ?><span class="tag cashback">5% cashback</span><?php endif; ?>
      <?php if($product->is_new || $product->badge === 'New Arrival'): ?><span class="tag new">New Arrival</span><?php endif; ?>
      <?php if($product->is_best_seller || $product->badge === 'Best Seller'): ?><span class="tag best">Best Seller</span><?php endif; ?>
    </div>
    <div class="product-actions">
      <button class="btn btn-cart" type="button"
        data-add="<?php echo e($product->id); ?>"
        data-name="<?php echo e($product->name); ?>"
        data-price="<?php echo e($product->price); ?>"
        data-image="<?php echo e(media_url($product->primaryImage())); ?>">
        Add to Cart
      </button>
      <?php if($product->amazon_url): ?>
        <a class="btn btn-amazon" href="<?php echo e($product->amazon_url); ?>" target="_blank" rel="noopener noreferrer">
          <span class="amz">a</span> Buy on Amazon
        </a>
      <?php endif; ?>
    </div>
  </div>
</article>
<?php /**PATH C:\laragon\www\ippeo_live\resources\views/partials/product-card.blade.php ENDPATH**/ ?>