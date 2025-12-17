<?php
session_start();

// 1. DB 연결
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

// 로그인 유저 아이디 (없으면 빈 문자열)
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';

// 2. 검색어 처리
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = "";

if ($search_keyword) {
    $safe_keyword = $conn->real_escape_string($search_keyword);
    $search_sql = "WHERE name LIKE '%$safe_keyword%'";
}

// 3. 영화 목록 가져오기 (+ 좋아요 여부 확인)
// 서브쿼리를 사용하여 현재 로그인한 유저가 이 영화를 좋아요 눌렀는지(1 or 0) 확인
// ★ [수정됨] 정렬 기준: 좋아요(hits) 많은 순 -> id 순
$sql = "SELECT m.*, 
        (SELECT COUNT(*) FROM movie_likes ml WHERE ml.movie_id = m.id AND ml.user_id = '$userid') as user_liked
        FROM movie m 
        $search_sql 
        ORDER BY hits DESC, id ASC";

$result = $conn->query($sql);

$movies = [];
$rank_counter = 1;

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $movies[] = [
            'id' => $row['id'],
            'rank' => $rank_counter++,
            'title' => $row['name'],
            'img' => $row['img'],
            'director' => $row['director'],
            'hits' => $row['hits'],       // 총 좋아요 수
            'user_liked' => $row['user_liked'] // 내가 좋아요 했는지 (1 or 0)
        ];
    }
}

// 4. 예매한 영화 개수 가져오기
$my_booking_count = 0; 
if ($userid) {
    $sql_count = "SELECT COUNT(*) as cnt FROM booking WHERE userid = ?";
    $stmt = $conn->prepare($sql_count);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result_count = $stmt->get_result();
    
    if ($row_count = $result_count->fetch_assoc()) {
        $my_booking_count = $row_count['cnt'];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGV - 홈</title>
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
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding-bottom: 40px;
        }

        /* --- 헤더 --- */
        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { color: white; font-weight: bold; font-size: 24px; letter-spacing: -0.5px; }
        .user-info { font-size: 13px; color: rgba(255, 255, 255, 0.9); font-weight: 500; display: flex; align-items: center; gap: 10px; }
        .user-info a { color: white; text-decoration: none; padding: 4px 8px; border: 1px solid rgba(255,255,255,0.4); border-radius: 4px; font-size: 11px; transition: background 0.2s; }
        .user-info a:hover { background-color: rgba(255, 255, 255, 0.2); }

        /* --- 검색창 영역 --- */
        .search-section {
            padding: 20px 20px 10px 20px;
            background-color: white;
        }
        .search-form {
            display: flex;
            gap: 8px;
        }
        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fafb;
            outline: none;
            transition: all 0.2s;
        }
        .search-input:focus {
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
        }
        .search-btn {
            padding: 0 20px;
            background-color: #374151;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        /* --- 무비차트 영역 --- */
        .chart-section { padding: 24px 0 20px 0; background: white; }
        .section-header { padding: 0 20px 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .section-title { font-size: 19px; font-weight: bold; color: #1f2937; }
        .view-all { font-size: 13px; color: #9ca3af; cursor: pointer; text-decoration: none; }

        .movie-scroll-container { display: flex; overflow-x: auto; padding: 0 20px; gap: 16px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding-bottom: 10px; }
        .movie-scroll-container::-webkit-scrollbar { display: none; }

        .movie-card { min-width: 160px; width: 160px; scroll-snap-align: start; position: relative; }
        
        .poster-wrapper { 
            position: relative; 
            width: 100%; 
            height: 230px; 
            border-radius: 12px; 
            overflow: hidden; 
            margin-bottom: 12px; 
            background-color: #f3f4f6; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }
        .poster-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s; }
        .movie-card:active .poster-img { transform: scale(0.98); }
        
        .rank-badge { 
            position: absolute; 
            bottom: -6px; 
            left: 8px; 
            font-size: 44px; 
            font-weight: 800; 
            color: white; 
            font-style: italic; 
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5); 
            line-height: 1; 
        }

        /* [추가] 하트 버튼 스타일 */
        .btn-like-heart {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 24px;
            color: white; /* 기본 하얀색 */
            text-decoration: none;
            z-index: 10;
            filter: drop-shadow(0 0 2px rgba(0,0,0,0.5));
            transition: transform 0.2s;
        }
        .btn-like-heart:hover { transform: scale(1.2); }
        
        /* 좋아요 눌렀을 때 스타일 */
        .liked { color: #dc2626; }

        .movie-info { text-align: center; }
        .movie-title { font-size: 16px; font-weight: bold; color: #111; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
        
        .movie-stats { 
            font-size: 13px; 
            color: #6b7280; 
            margin-bottom: 12px; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }
        .director-name { color: #374151; font-weight: 500; }

        .btn-book { display: block; width: 100%; padding: 10px 0; background-color: #dc2626; color: white; text-align: center; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: bold; transition: background 0.2s; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2); }
        .btn-book:hover { background-color: #b91c1c; }
        .btn-book:active { transform: scale(0.98); }

        /* --- 배너 영역 --- */
        .count-section {
            padding: 10px 20px 20px 20px;
            background-color: white;
        }
        .count-card {
            background-color: #dc2626;
            text-decoration: none;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
        }
        .count-card:hover { background-color: #b91c1c;}
        .count-text-group {
            display: flex;
            flex-direction: column;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .count-number {
            font-size: 64px;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -2px;
        }

        /* --- 하단 버튼 영역 --- */
        .board-link-section {
            padding: 0 20px 40px 20px;
            background-color: white;
        }
        .btn-link {
            display: block;
            width: 100%;
            padding: 16px 0;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        .btn-link:active { transform: scale(0.98); }

        .btn-board {
            background-color: #374151; 
            margin-bottom: 12px; 
        }
        .btn-board:hover { background-color: #1f2937; }

        .btn-coupon {
            background-color: #4b5563; 
        }
        .btn-coupon:hover { background-color: #374151; }

    </style>
</head>
<body>

    <div class="app-container">
        <!-- 헤더 -->
        <div class="header">
            <h1>CGV</h1>
            <div class="user-info">
                <?php 
                if(isset($_SESSION['name'])) {
                    echo "<span>" . $_SESSION['name'] . "님</span>";
                    echo "<a href='logout.php'>로그아웃</a>";
                } else {
                    echo "<a href='login.php'>로그인</a>";
                }
                ?>
            </div>
        </div>

        <!-- 검색창 -->
        <div class="search-section">
            <form action="home.php" method="GET" class="search-form">
                <input type="text" name="search" class="search-input" 
                       placeholder="영화 제목을 검색해보세요" value="<?= htmlspecialchars($search_keyword) ?>">
                <button type="submit" class="search-btn">검색</button>
            </form>
        </div>

        <!-- 무비차트 -->
        <div class="chart-section">
            <div class="section-header">
                <span class="section-title">
                    <?php echo $search_keyword ? "'" . htmlspecialchars($search_keyword) . "' 검색" : "무비차트"; ?>
                </span>
                <?php if(!$search_keyword): ?>
                    <a href="movie_list.php" class="view-all">전체보기 ></a>
                <?php else: ?>
                    <a href="home.php" class="view-all">전체 목록 보기</a>
                <?php endif; ?>
            </div>

            <div class="movie-scroll-container">
                <?php if (empty($movies)): ?>
                    <p style="padding: 40px 0; color: #888; width:100%; text-align:center;">
                        <?php echo $search_keyword ? "검색된 영화가 없습니다." : "등록된 영화가 없습니다."; ?>
                    </p>
                <?php else: ?>
                    <?php foreach($movies as $movie): ?>
                        <div class="movie-card">
                            <div class="poster-wrapper">
                                <img src="images/<?= $movie['img'] ?>" class="poster-img" alt="<?= $movie['title'] ?>" 
                                     onerror="this.src='https://via.placeholder.com/150x220?text=No+Image'">
                                <div class="rank-badge"><?= $movie['rank'] ?></div>
                                
                                <!-- ★ 하트 버튼 (좋아요 기능) -->
                                <a href="movie_like.php?id=<?= $movie['id'] ?>" class="btn-like-heart <?= $movie['user_liked'] ? 'liked' : '' ?>">
                                    ♥
                                </a>
                            </div>
                            <div class="movie-info">
                                <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
                                <div class="movie-stats">
                                    감독: <?= htmlspecialchars($movie['director']) ?><br>
                                    <span style="font-size:12px; color:#dc2626;">♥ <?= $movie['hits'] ?></span>
                                </div>
                                <a href="booking.php?id=<?= $movie['id'] ?>" class="btn-book">지금 예매</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div style="min-width: 5px;"></div>
            </div>
        </div>

        <!-- 예매 카운트 배너 -->
        <div class="count-section">
            <a href="my_movies.php" class="count-card">
                <div class="count-text-group">
                    <span>지금까지</span>
                    <span>본</span>
                    <span>영화</span>
                </div>
                <div class="count-number">
                    <?= $my_booking_count ?>
                </div>
            </a>
        </div>

        <!-- 하단 버튼들 -->
        <div class="board-link-section">
            <a href="board_list.php" class="btn-link btn-board">한줄평 보러가기</a>
            <a href="coupon_mine.php" class="btn-link btn-coupon">쿠폰 보러가기</a>
        </div>
    </div>

</body>
</html>