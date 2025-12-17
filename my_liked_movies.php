<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$userid = $_SESSION['userid'];

// 좋아요 한 영화 정보 가져오기
$sql = "SELECT m.* FROM movie_likes ml 
        JOIN movie m ON ml.movie_id = m.id 
        WHERE ml.user_id = '$userid' 
        ORDER BY ml.liked_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>좋아요 한 영화 - CGV</title>
    <style>
        /* (movie_list.php와 동일 스타일) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Malgun Gothic', sans-serif; background-color: #f5f5f5; min-height: 100vh; }
        .app-container { max-width: 480px; margin: 0 auto; background-color: white; min-height: 100vh; box-shadow: 0 0 20px rgba(0,0,0,0.1); position: relative; }
        
        .header { background-color: #dc2626; color: white; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        .content { padding: 20px; }
        .movie-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 15px; }
        .movie-item { background: white; display: flex; flex-direction: column; }
        .poster-wrapper { position: relative; width: 100%; aspect-ratio: 2 / 3; border-radius: 8px; overflow: hidden; background-color: #eee; margin-bottom: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .poster-img { width: 100%; height: 100%; object-fit: cover; }
        
        /* 꽉 찬 하트 아이콘 */
        .like-icon { position: absolute; bottom: 8px; right: 8px; color: #dc2626; font-size: 22px; filter: drop-shadow(0 0 2px rgba(255,255,255,0.8)); }

        .movie-title { font-size: 16px; font-weight: bold; color: #111; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .movie-director { font-size: 12px; color: #666; margin-bottom: 10px; }
        
        .btn-book { display: block; width: 100%; padding: 10px 0; background-color: #dc2626; color: white; text-align: center; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; margin-top: auto; }
        .empty-msg { text-align: center; color: #999; padding: 50px 0; grid-column: span 2; }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <h1>좋아요 한 영화</h1>
            <a href="my_movies.php" class="btn-back">닫기</a>
        </div>
        <div class="content">
            <div class="movie-grid">
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="movie-item">
                            <div class="poster-wrapper">
                                <img src="images/<?= $row['img'] ?>" class="poster-img" onerror="this.src='https://via.placeholder.com/150x220?text=No+Image'">
                                <span class="like-icon">♥</span>
                            </div>
                            <div class="movie-title"><?= htmlspecialchars($row['name']) ?></div>
                            <div class="movie-director">감독: <?= htmlspecialchars($row['director']) ?></div>
                            <a href="booking.php?id=<?= $row['id'] ?>" class="btn-book">지금 예매</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-msg">좋아요 한 영화가 없습니다.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>