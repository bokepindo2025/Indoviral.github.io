<aside class="sidebar">
  <div class="nav-section nav-left">
    <button class="nav-button menu-button">
      <i class="uil uil-bars"></i>
    </button>
    <a href="/" class="nav-logo">
      <h2 class="logo-text"><i class="fa fa-chevron-circle-right"></i><?= htmlspecialchars($config['site_title']) ?></h2>
    </a>
  </div>
  <div class="links-container">
    <div class="link-section">
      <a href="/dashboard" class="link-item"> <i class="uil uil-estate"></i> Home </a>
      <a href="/dashboard/setting.php" class="link-item"> <i class="uil uil-setting"></i> Setting </a>
      <a href="/dashboard/adsterra.php" class="link-item"> <i class="uil uil-dollar-sign-alt"></i> Adsterra </a>
    </div>
    <div class="section-separator"></div>
    <div class="link-section">
      <h4 class="section-title">Other</h4>
      <a href="/sitemap.php" target="_blank" class="link-item"> <i class="uil uil-sitemap"></i> Sitemap </a>
      <a href="/dashboard/logout.php" class="link-item"> <i class="uil uil-signin"></i> Logout </a>
    </div>
  </div>
</aside>