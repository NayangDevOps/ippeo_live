<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $__env->yieldContent('title', $site['site_name'] ?? 'Ippeo Essential Products'); ?></title>
  <meta name="description" content="<?php echo $__env->yieldContent('meta', 'Nature-inspired skincare by Ippeo Essential Products'); ?>" />
  <link rel="icon" href="<?php echo e(media_url($site['logo'] ?? 'images/logo.png')); ?>" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo e(asset('css/style.css') . '?v=6'); ?>" />
  <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body>
  <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <main>
    <?php if(session('success')): ?>
      <div class="flash success container"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
      <div class="flash error container"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
  </main>
  <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <div class="toast" id="toast" role="status" aria-live="polite"></div>
  <script src="<?php echo e(asset('js/store.js') . '?v=7'); ?>"></script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\ippeo_live\resources\views/layouts/store.blade.php ENDPATH**/ ?>