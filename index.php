<?php
$apiKey = "571c73b632fa4635863641064ba9f355"; 
$category = isset($_GET['category']) ? $_GET['category'] : 'technology';
$url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=571c73b632fa4635863641064ba9f355";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
  die("cURL Error: " . curl_error($ch));
}
curl_close($ch);
$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Berita Teknologi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9f9; }
    .navbar { margin-bottom: 30px; }
    .card { border-radius: 12px; overflow: hidden; transition: 0.3s; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .card img { height: 200px; object-fit: cover; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">📰 NewsAPI Viewer</a>
      <form class="d-flex" method="GET">
        <select name="category" class="form-select me-2">
          <option value="technology" <?= $category === 'technology' ? 'selected' : '' ?>>Technology</option>
          <option value="business" <?= $category === 'business' ? 'selected' : '' ?>>Business</option>
          <option value="sports" <?= $category === 'sports' ? 'selected' : '' ?>>Sports</option>
          <option value="science" <?= $category === 'science' ? 'selected' : '' ?>>Science</option>
          <option value="entertainment" <?= $category === 'entertainment' ? 'selected' : '' ?>>Entertainment</option>
        </select>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
      </form>
    </div>
  </nav>

  <div class="container">
    <h2 class="mb-4 text-center text-capitalize">Berita <?= $category ?></h2>

    <div class="row">
      <?php
      if ($data["status"] === "ok") {
        foreach ($data["articles"] as $article) {
          if (!empty($article["title"]) && !empty($article["description"])) {
            echo '
              <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                  '.(!empty($article["urlToImage"]) ? '<img src="'.$article["urlToImage"].'" class="card-img-top">' : '').'
                  <div class="card-body">
                    <h5 class="card-title">'.htmlspecialchars($article["title"]).'</h5>
                    <p class="card-text">'.htmlspecialchars($article["description"]).'</p>
                    <p><small class="text-muted">Penulis: '.($article["author"] ?: 'Tidak diketahui').'</small></p>
                    <a href="'.$article["url"].'" target="_blank" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                  </div>
                </div>
              </div>
            ';
          }
        }
      } else {
        echo '<div class="alert alert-danger text-center">Gagal memuat berita. Periksa API Key kamu.</div>';
      }
      ?>
    </div>
  </div>

  <footer class="text-center mt-5 py-3 bg-light text-muted">
    Dibuat oleh Akbar | Data: NewsAPI.org
  </footer>

</body>
</html>
