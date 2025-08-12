<?php
$api_url = "https://api.jejaring.cc/videos.php";
$response = file_get_contents($api_url);
$data = json_decode($response, true);

// Force https protocol untuk sitemap
$protocol = "https://";

if (!$data || !isset($data['videos']) || !is_array($data['videos'])) {
    echo "No video data available.";
    exit;
}

header('Content-Type: application/xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

foreach ($data['videos'] as $video) {
    // Cek apakah slug valid
    if (empty($video['slug']) || !is_string($video['slug'])) {
        continue; // Skip video tanpa slug yang valid
    }

    // Cek apakah date valid
    $date_string = !empty($video['date']) ? $video['date'] : null;
    $timestamp = strtotime($date_string);
    if ($timestamp === false) {
        // Jika date tidak valid, gunakan tanggal hari ini sebagai fallback
        $lastmod = date('Y-m-d');
    } else {
        $lastmod = date('Y-m-d', $timestamp);
    }

    // Build URL aman dengan https
    $video_url = $protocol . $_SERVER['HTTP_HOST'] . "/watch/?v=" . urlencode((string)$video['slug']);
    
    // Output XML
    echo '<url>';
    echo '<loc>' . htmlspecialchars($video_url) . '</loc>';
    echo '<lastmod>' . $lastmod . '</lastmod>';
    echo '<changefreq>daily</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

echo '</urlset>';
?>
