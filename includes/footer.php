<footer class="site-footer mt-auto py-4">
  <div class="container">
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <strong><?= e(SITE_NAME) ?></strong><br>
        <small>CCTV, networking and power backup. Deliveries countrywide.</small>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="tel:<?= e(SHOP_PHONE_INTL) ?>" class="text-white"><?= e(SHOP_PHONE) ?></a><br>
        <small>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?></small>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>