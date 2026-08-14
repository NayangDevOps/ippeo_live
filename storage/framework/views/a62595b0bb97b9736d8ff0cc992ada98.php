<?php $__env->startSection('title', ($site['site_name'] ?? 'Ippeo') . " | Nature's Secret; Ippeo's Promise"); ?>

<?php $__env->startSection('content'); ?>
<section class="hero" aria-label="Promotions">
  <div class="hero-slider" id="heroSlider">
    <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <article class="hero-slide <?php echo e($i === 0 ? 'is-active' : ''); ?>" style="--hero-bg: url('<?php echo e(media_url($banner->image)); ?>')">
        <div class="hero-content">
          <?php if($banner->script_text): ?><p class="hero-script"><?php echo e($banner->script_text); ?></p><?php endif; ?>
          <h1><?php echo e($banner->title); ?></h1>
          <?php if($banner->subtitle): ?><p class="hero-sub"><?php echo e($banner->subtitle); ?></p><?php endif; ?>
          <?php if($banner->button_text): ?>
            <a href="<?php echo e($banner->button_link ?: route('shop')); ?>" class="btn btn-primary"><?php echo e($banner->button_text); ?></a>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <article class="hero-slide is-active" style="--hero-bg: url('<?php echo e(asset('images/hero-slide-1.jpg')); ?>')">
        <div class="hero-content">
          <p class="hero-script">Embrace Nature</p>
          <h1>Nourish Your Skin</h1>
          <p class="hero-sub">Nature's care for healthy, glowing skin every day.</p>
          <a href="<?php echo e(route('shop')); ?>" class="btn btn-primary">SHOP NOW</a>
        </div>
      </article>
    <?php endif; ?>
  </div>
  <button class="hero-arrow prev" type="button" aria-label="Previous slide">&#10094;</button>
  <button class="hero-arrow next" type="button" aria-label="Next slide">&#10095;</button>
  <div class="hero-dots" id="heroDots"></div>
</section>

<section class="products-section" id="products">
  <div class="container">
    <header class="section-head">
      <h2><?php echo e($settings['home_products_title']); ?></h2>
      <p><?php echo e($settings['home_products_subtitle']); ?></p>
    </header>
    <div class="product-grid">
      <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div style="text-align:center;margin-top:1.5rem">
      <a class="btn btn-primary" href="<?php echo e(route('shop')); ?>">View All Products</a>
    </div>
  </div>
</section>

<section class="about-section" id="about">
  <div class="container about-grid">
    <div class="about-copy">
      <h2><?php echo e($settings['home_about_title']); ?></h2>
      <?php $__currentLoopData = preg_split("/\n\s*\n/", $settings['home_about_text'] ?? ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $para): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(trim($para)): ?>
          <p><?php echo e(trim($para)); ?></p>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('page.show', 'about')); ?>" class="show-more">SHOW MORE</a>
    </div>
    <div class="about-visual">
      <img src="<?php echo e(media_url($settings['home_about_image'])); ?>" alt="Ippeo brand" loading="lazy" />
    </div>
  </div>
</section>

<section class="inquiry-section" id="inquiry">
  <div class="container">
    <header class="section-head center">
      <h2><?php echo e($settings['home_inquiry_title']); ?></h2>
      <p><?php echo e($settings['home_inquiry_subtitle']); ?></p>
    </header>
    <form class="inquiry-form" action="<?php echo e(route('enquiry.submit')); ?>" method="post">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="source" value="homepage" />
      <div class="form-grid">
        <div class="form-col">
          <label for="name">Your Name</label>
          <input id="name" name="name" type="text" required value="<?php echo e(old('name')); ?>" />
          <label for="email">Email Address</label>
          <input id="email" name="email" type="email" required value="<?php echo e(old('email')); ?>" />
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" value="<?php echo e(old('phone')); ?>" />
        </div>
        <div class="form-col">
          <label for="message">Your Message</label>
          <textarea id="message" name="message" rows="8" required placeholder="How can we help you?"><?php echo e(old('message')); ?></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-wide">SEND INQUIRY</button>
    </form>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const slides = [...document.querySelectorAll('.hero-slide')];
  const dots = document.getElementById('heroDots');
  if (!slides.length || !dots) return;
  let i = 0, t;
  const go = n => {
    i = (n + slides.length) % slides.length;
    slides.forEach((s, idx) => s.classList.toggle('is-active', idx === i));
    [...dots.children].forEach((d, idx) => d.classList.toggle('is-active', idx === i));
  };
  slides.forEach((_, n) => {
    const b = document.createElement('button');
    b.type = 'button';
    if (!n) b.classList.add('is-active');
    b.onclick = () => { go(n); restart(); };
    dots.appendChild(b);
  });
  document.querySelector('.hero-arrow.prev')?.addEventListener('click', () => { go(i-1); restart(); });
  document.querySelector('.hero-arrow.next')?.addEventListener('click', () => { go(i+1); restart(); });
  function restart(){ clearInterval(t); t = setInterval(() => go(i+1), 5500); }
  restart();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ippeo_live\resources\views/shop/home.blade.php ENDPATH**/ ?>