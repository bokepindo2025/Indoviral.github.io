<?php
$config_file = 'templates/core/config.php';
$config = include($config_file);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

function fetchVideos($page, $category = '', $limit = 12, $order = 'random') {
    $categoryParam = $category ? '&category=' . urlencode($category) : '';
    $api_url = "https://api.jejaring.cc/videos.php?page=$page&limit=$limit&order=$order$categoryParam";
    $response = @file_get_contents($api_url);
    $data = json_decode($response, true);

    if (!$data || !isset($data['videos'])) {
        return [];
    }

    return $data['videos'];
}

// Fetch data
$videos_bokep_indo = fetchVideos($page, '', 12, 'daily_random');
$videos_bokep_smp = fetchVideos($page, 'Bokep SMP');
$videos_bokep_asd = fetchVideos($page, 'Bokep ASD');

// Helper function untuk render section video
function renderVideoSection($title, $videos, $linkCategory) {
    ?>
    <div class="title-wrap" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <h2 class="section-title top"><?= htmlspecialchars($title) ?></h2>
        <a href="<?= htmlspecialchars($linkCategory) ?>" class="more-videos-btn">More Videos</a>
    </div>

    <div class="video-list">
        <?php foreach ($videos as $video): ?>
            <a href="/watch/?v=<?= htmlspecialchars($video['slug'] ?? '') ?>" class="video-card">
                <div class="thumbnail-container">
                    <img src="<?= htmlspecialchars($video['poster'] ?? '') ?>" alt="Video Thumbnail" class="thumbnail" />
                    <div class="thumbnail-overlay">
                        <i class="fa fa-play play-icon"></i>
                    </div>
                </div>
                <div class="video-info">
                    <div class="video-details">
                        <h2 class="title"><?= htmlspecialchars($video['title'] ?? '') ?></h2>
                        <p class="views">
                            <i class="uil uil-eye"></i> <?= htmlspecialchars($video['views'] ?? '0') ?>
                            <i class="uil uil-calendar-alt"></i>
                            <?= !empty($video['date']) ? date("d M Y", strtotime($video['date'])) : '' ?>
                        </p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="<?= htmlspecialchars($config['site_description'] ?? '') ?>">
  <meta name="keywords" content="<?= htmlspecialchars($config['site_keywords'] ?? '') ?>">
  <meta name="author" content="Bokepoi.com">
  <title><?= htmlspecialchars($config['site_title'] ?? '') ?> - <?= htmlspecialchars($config['site_description'] ?? '') ?></title>

  <link rel="icon" href="/templates/assets/favicon.ico" type="image/x-icon" />
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/templates/assets/styles.php'); ?>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <div style="color:transparent;"><?= htmlspecialchars_decode($config['site_popunder'] ?? '') ?></div>

  <style>
    .more-videos-btn {
      display: inline-block;
      padding: 10px 18px;
      font-size: 15px;
      color: #fff;
      background-color: #ff6600;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
      margin-top: 10px;
      text-decoration: none;
    }

    .more-videos-btn:hover {
      background-color: #e65c00;
    }
  </style>
</head>
<body class="dark-mode">
  <div class="container">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/templates/parts/header.php'); ?>
    <main class="main-layout">
      <div class="screen-overlay"></div>
      <?php include($_SERVER['DOCUMENT_ROOT'] . '/templates/parts/sidebar.php'); ?>
      <div class="content-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/templates/parts/categories.php'); ?>

        <!-- Swiper Slider -->
        <div class="swiper-container">
          <div class="swiper-wrapper" id="videoSlider">
            <?php foreach ($videos_bokep_indo as $video): ?>
              <div class="swiper-slide">
                <a href="/watch/?v=<?= htmlspecialchars($video['slug'] ?? '') ?>">
                  <img src="https://poster.imgvid.com/<?= htmlspecialchars($video['code'] ?? '') ?>.jpg" alt="Video Thumbnail" />
                  <div class="slider-title"><?= htmlspecialchars($video['title'] ?? '') ?></div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Sections -->
        <?php
          renderVideoSection('Bokep Indo', $videos_bokep_indo, '/categories?category=Bokep+Indo');
          renderVideoSection('Bokep Bocil', $videos_bokep_smp, '/categories?category=Bokep+SMP');
          renderVideoSection('Bokep Asia', $videos_bokep_asd, '/categories?category=Bokep+ASD');
        ?>

      </div>
    </main>
  </div>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        slidesPerGroup: 1,
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        speed: 2000,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
          1280: {
            slidesPerView: 4,
          }
        }
      });

      const activeBtn = document.getElementById('semua');
      if (activeBtn) activeBtn.classList.add('active');
    });
  </script>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/templates/assets/javascript.php'); ?>
  <div style="color:transparent;"><?= htmlspecialchars_decode($config['site_socialbar'] ?? '') ?></div>
  <div style="color:transparent;"><?= htmlspecialchars_decode($config['site_histatsjs'] ?? '') ?></div>
</body>
</html>
