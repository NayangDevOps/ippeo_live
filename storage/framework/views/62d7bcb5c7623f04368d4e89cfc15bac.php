<?php
  $company = json_decode($site['footer_company_links'] ?? '[]', true) ?: [];
  $care = json_decode($site['footer_care_links'] ?? '[]', true) ?: [];
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="<?php echo e(route('home')); ?>"><img src="<?php echo e(media_url($site['logo'] ?? 'images/logo.png')); ?>" alt="<?php echo e($site['site_name'] ?? 'Ippeo'); ?>" /></a>
      <p class="footer-tagline"><?php echo e($site['tagline'] ?? ''); ?></p>
      <div class="socials">
        <a href="<?php echo e($site['instagram'] ?? '#'); ?>" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        </a>
        <a href="<?php echo e($site['facebook'] ?? '#'); ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.5l.5-3H14V9z"/></svg>
        </a>
      </div>
    </div>
    <div>
      <h4>Company</h4>
      <ul>
        <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><a href="<?php echo e($link['url'] ?? '#'); ?>"><?php echo e($link['label'] ?? ''); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <div>
      <h4>Customer Care</h4>
      <ul>
        <?php $__currentLoopData = $care; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><a href="<?php echo e($link['url'] ?? '#'); ?>"><?php echo e($link['label'] ?? ''); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <div>
      <h4>Contact Us</h4>
      <ul class="contact-list">
        <?php if(!empty($site['phone_1'])): ?><li><a href="tel:<?php echo e(preg_replace('/\s+/', '', $site['phone_1'])); ?>"><?php echo e($site['phone_1']); ?></a></li><?php endif; ?>
        <?php if(!empty($site['phone_2'])): ?><li><a href="tel:<?php echo e(preg_replace('/\s+/', '', $site['phone_2'])); ?>"><?php echo e($site['phone_2']); ?></a></li><?php endif; ?>
        <li><a href="mailto:<?php echo e($site['email'] ?? 'info@ippeo.in'); ?>"><?php echo e($site['email'] ?? 'info@ippeo.in'); ?></a></li>
        <li><?php echo e($site['address'] ?? ''); ?></li>
      </ul>
    </div>
    <div class="newsletter">
      <h4>Subscribe to get updates...</h4>
      <form class="newsletter-form" action="<?php echo e(route('newsletter')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <input type="email" name="email" placeholder="Enter your email" aria-label="Newsletter email" required />
        <button type="submit" aria-label="Subscribe">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
        </button>
      </form>
    </div>
  </div>
  <div class="footer-bar">
    <div class="container footer-bar-inner">
      <p><?php echo e($site['copyright'] ?? ''); ?></p>
      <button class="back-top" type="button" aria-label="Back to top" id="backTop">↑</button>
    </div>
  </div>
</footer>
<?php /**PATH C:\laragon\www\ippeo_live\resources\views/partials/footer.blade.php ENDPATH**/ ?>