<?php
header('Content-Type: application/json');

// Database config
$host = 'localhost';
$user = 'asupan21';
$pass = 'ayahnyafatim';
$db   = 'asupan21';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB Connection failed"]);
    exit;
}

// Ambil parameter
$slug  = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : null;
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 1000;
$offset = ($page - 1) * $limit;
$order = (isset($_GET['order']) && $_GET['order'] === 'random') ? 'RAND()' : 'created_at DESC';

// Optional filter by content type
$where = "";
if (isset($_GET['content']) && $_GET['content'] === '1') {
    $where = "WHERE content_type = '1'";
}

// Jika slug disediakan, ambil 1 video
if ($slug) {
    $sql = "SELECT * FROM videos WHERE slug = '$slug' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "video" => [
                "slug" => $row['slug'],
                "title" => $row['title'],
                "videos" => $row['video_url'],
                "embed" => $row['embed_url'],
                "poster" => $row['thumbnail_url'],
                "download" => $row['download_url'],
                "actor" => $row['actor'],
                "description" => $row['description'],
                "code" => $row['code'],
                "year" => $row['year'],
                "country" => $row['country'],
                "genre" => $row['genre'],
                "time" => $row['duration'],
                "views" => $row['views'],
                "date" => $row['created_at']
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Video not found"]);
    }
    exit;
}

// Tanpa slug: ambil daftar video
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM videos $where");
$totalVideos = $totalRes ? (int)$totalRes->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalVideos / $limit);

$sql = "SELECT * FROM videos $where ORDER BY $order LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$videos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = [
            "slug" => $row['slug'],
            "title" => $row['title'],
            "videos" => $row['video_url'],
            "embed" => $row['embed_url'],
            "poster" => $row['thumbnail_url'],
            "download" => $row['download_url'],
            "actor" => $row['actor'],
            "description" => $row['description'],
            "code" => $row['code'],
            "year" => $row['year'],
            "country" => $row['country'],
            "genre" => $row['genre'],
            "time" => $row['duration'],
            "views" => $row['views'],
            "date" => $row['created_at']
        ];
    }
}

echo json_encode([
    "status" => "success",
    "current_page" => $page,
    "total_pages" => $totalPages,
    "videos_on_page" => count($videos),
    "total_videos" => $totalVideos,
    "videos" => $videos
]);
?>
