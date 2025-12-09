<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

$sql = "SELECT * FROM movie ORDER BY id ASC";
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
            'director' => $row['director']
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

        /* 헤더 */
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

        /* 컨텐츠 */
        .content {
            padding: 20px;
        }

        /* 2열 그리드 */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 15px; 
        }

        /* 영화 카드 */
        .movie-item {
            background: white;
            display: flex;
            flex-direction: column;
        }

        .poster-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3;
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
        }

        /* 예매 버튼 */
        .btn-book {
            margin-top: auto; 
            display: block;
            width: 100%;
            padding: 10px 0;
            background-color: #dc2626; 
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-book:hover { background-color: #b91c1c; }

        /* 영화 추가 버튼*/
        .board-movie-add {
            padding: 0 20px 40px 20px;
            background-color: white;
        }
        .btn-add {
            display: block;
            width: 100%;
            padding: 16px 0;
            background-color: #374151;
            margin-top: 30px;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        .btn-add:hover {
            background-color: #1f2937;
        }
        .btn-add:active {
            transform: scale(0.98);
        }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>무비차트</h1>
            <a href="admin_page.php" class="btn-back">닫기</a>
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
                        </div>

                        <!-- 정보 -->
                        <div class="movie-title"><?= $movie['title'] ?></div>
                        <div class="movie-director">감독: <?= $movie['director'] ?></div>

                        <!-- 삭제 버튼 -->
                        <a href="movie_delete.php?id=<?= $movie['id'] ?>" class="btn-book">삭제</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="board-movie-add">
                <a href="movie_write.php" class="btn-add">영화 등록</a>
            </div>
        </div>
    </div>

</body>
</html>