<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

// 로그인 유저 아이디 (없으면 빈 문자열)
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';

// 전체 영화 가져오기
// ★ [추가] 좋아요 여부(user_liked) 확인 서브쿼리 + 정렬 기준 변경(hits DESC)
$sql = "SELECT m.*, 
        (SELECT COUNT(*) FROM movie_likes ml WHERE ml.movie_id = m.id AND ml.user_id = '$userid') as user_liked
        FROM movie m 
        ORDER BY hits DESC, id ASC";

$result = $conn->query($sql);

$movies = [];
$rank = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $movies[] = [
            'id' => $row['id'],
            'rank' => $rank++,
            'title' => $row['name'],
            'img' => $row['img'],
            'director' => $row['director'],
            'hits' => $row['hits'],       // 총 좋아요 수
            'user_liked' => $row['user_liked'] // 내가 좋아요 했는지
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전체 영화 - CGV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        /* 헤더 (빨간색) */
        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header h1 { font-size: 20px; font-weight: bold; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        /* 컨텐츠 영역 */
        .content {
            padding: 20px;
        }

        /* 2열 그리드 레이아웃 */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2개씩 배치 */
            gap: 20px 15px; /* 세로간격 20px, 가로간격 15px */
        }

        /* 영화 카드 스타일 */
        .movie-item {
            background: white;
            display: flex;
            flex-direction: column;
        }

        .poster-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3; /* 포스터 비율 유지 */
            border-radius: 8px;
            overflow: hidden;
            background-color: #eee;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .poster-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .poster-wrapper:hover .poster-img {
            transform: scale(1.05);
        }

        .rank-badge {
            position: absolute;
            bottom: -4px;
            left: 8px;
            font-size: 32px;
            font-weight: 800;
            color: white;
            font-style: italic;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            line-height: 1;
        }

        /* ★ [추가] 하트 버튼 스타일 */
        .btn-like-heart {
            position: absolute;
            bottom: 8px;
            right: 8px;
            font-size: 22px;
            color: white; /* 기본 하얀색 */
            text-decoration: none;
            z-index: 10;
            filter: drop-shadow(0 0 2px rgba(0,0,0,0.5));
            transition: transform 0.2s;
        }
        .btn-like-heart:hover { transform: scale(1.2); }
        
        /* 좋아요 눌렀을 때 스타일 */
        .liked { color: #dc2626; }

        .movie-title {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-director {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* 예매 버튼 (진한 회색) */
        .btn-book {
            margin-top: auto; /* 하단 고정 */
            display: block;
            width: 100%;
            padding: 10px 0;
            background-color: #dc2626; /* 빨간색 버튼 */
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-book:hover { background-color: #b91c1c; }

    </style>
</head>
<body>

    <div class="app-container">
        <!-- 헤더 -->
        <div class="header">
            <h1>무비차트</h1>
            <a href="home.php" class="btn-back">닫기</a>
        </div>

        <div class="content">
            <div class="movie-grid">
                <?php foreach($movies as $movie): ?>
                    <div class="movie-item">
                        <!-- 포스터 -->
                        <div class="poster-wrapper">
                            <img src="images/<?= $movie['img'] ?>" class="poster-img" alt="<?= $movie['title'] ?>"
                                 onerror="this.src='https://via.placeholder.com/150x220?text=No+Image'">
                            <div class="rank-badge"><?= $movie['rank'] ?></div>
                            
                            <!-- ★ 하트 버튼 (좋아요 기능) -->
                            <a href="movie_like.php?id=<?= $movie['id'] ?>" class="btn-like-heart <?= $movie['user_liked'] ? 'liked' : '' ?>">
                                ♥
                            </a>
                        </div>

                        <!-- 정보 -->
                        <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
                        <div class="movie-director">
                            <span>감독: <?= htmlspecialchars($movie['director']) ?></span>
                            <!-- 좋아요 수 표시 -->
                            <span style="color:#dc2626;">♥ <?= $movie['hits'] ?></span>
                        </div>

                        <!-- 예매 버튼 -->
                        <a href="booking.php?id=<?= $movie['id'] ?>" class="btn-book">지금 예매</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>