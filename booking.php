<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

// 로그인 체크
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 영화 정보 가져오기
if (!isset($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$movie_id = $_GET['id'];
$userid = $_SESSION['userid'];

// 영화 제목 조회
$sql_movie = "SELECT name FROM movie WHERE id = $movie_id";
$result_movie = $conn->query($sql_movie);
$movie = $result_movie->fetch_assoc();

// 학생 할인 정보 가져오기
$sql_user = "SELECT is_univ, is_swu FROM cgvtable WHERE userid = '$userid'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$is_univ = $user['is_univ'];
$is_swu = $user['is_swu'];

// 사용 가능한 쿠폰 가져오기
$sql_coupon = "SELECT * FROM couponbox WHERE userid = '$userid' AND is_used = 0 ORDER BY issued_date DESC";
$result_coupon = $conn->query($sql_coupon);

// 할인율 계산
$discount_rate = 0;
if ($is_univ) $discount_rate += 0.1; 
if ($is_swu) $discount_rate += 0.1;

$base_price = 10000;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예매하기 - CGV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #e5e7eb;
            min-height: 100vh;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding-bottom: 80px;
        }

        /* 헤더 */
        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 24px; font-weight: bold; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; }

        /* 컨텐츠 */
        .content { padding: 24px 20px; }

        .movie-title {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            margin-bottom: 24px;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
        }

        /* 시간표 그리드 */
        .time-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 30px;
        }

        .time-btn {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 14px 0;
            font-size: 16px;
            font-family: 'Malgun Gothic', sans-serif;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
            color: #333;
        }

        .time-btn:hover { background-color: #e5e7eb; }

        .time-btn.selected {
            background-color: #374151;
            color: white;
            border-color: #374151;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* 할인 정보 박스 */
        .discount-info {
            margin-bottom: 30px;
            font-size: 16px;
            color: #374151;
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 12px;
        }
        .discount-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        /* 쿠폰 선택 */
        .coupon-area {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #d1d5db;
        }
        .coupon-select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-family: inherit;
            font-size: 14px;
        }
        
        .checkbox-custom {
            width: 24px;
            height: 24px;
            accent-color: #dc2626;
            cursor: default;
        }

        /* 가격 계산 */
        .price-area {
            text-align: right;
            margin-bottom: 30px;
            font-weight: bold;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .price-row {
            margin-bottom: 8px;
            font-size: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }
        .base-price { color: #dc2626; }
        .discount-price { color: #dc2626; }
        
        .total-line {
            width: 140px;
            height: 2px;
            background-color: #dc2626;
            margin-left: auto;
            margin-bottom: 12px;
            margin-top: 12px;
        }

        .final-price {
            font-size: 32px;
            color: #dc2626;
            font-weight: 900;
            letter-spacing: -1px;
        }

        /* 인원 선택 바 */
        .counter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #dc2626;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        }

        .counter-btn {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .counter-btn:hover { background-color: rgba(0, 0, 0, 0.4); }

        .booking-form {
            text-align: right;
            margin-top: 20px;
        }

        .btn-submit {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn-submit:hover { background-color: #b91c1c; }

    </style>
</head>
<body>

<div class="app-container">
    <div class="header">
        <h1>CGV</h1>
        <a href="home.php" class="btn-back">돌아가기</a>
    </div>

    <div class="content">
        <div class="movie-title"><?= htmlspecialchars($movie['name']) ?></div>

        <div class="time-grid" id="timeContainer">
            <?php
            for ($h = 9; $h <= 20; $h++) {
                $time_str = $h . ":00";
                echo "<button type='button' class='time-btn' onclick='selectTime(this, \"$time_str\")'>$time_str</button>";
            }
            ?>
        </div>

        <div class="discount-info">
            <div class="discount-item">
                <span>대학생 할인 10%</span>
                <input type="checkbox" class="checkbox-custom" <?= $is_univ ? 'checked' : '' ?> onclick="return false;">
            </div>
            <div class="discount-item">
                <span>서울여대 할인 10%</span>
                <input type="checkbox" class="checkbox-custom" <?= $is_swu ? 'checked' : '' ?> onclick="return false;">
            </div>
            <div class="coupon-area">
                <div style="font-weight:bold; margin-bottom:5px;">쿠폰 할인</div>
                <select id="couponSelect" class="coupon-select" onchange="updatePrice()">
                    <option value="0" data-id="">쿠폰을 선택하세요</option>
                    <?php 
                    if ($result_coupon->num_rows > 0) {
                        while($cp = $result_coupon->fetch_assoc()) {
                            echo "<option value='{$cp['price']}' data-id='{$cp['id']}'>{$cp['title']} (" . number_format($cp['price']) . "원 할인)</option>";
                        }
                    } else {
                        echo "<option value='0' disabled>사용 가능한 쿠폰이 없습니다</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="price-area">
            <div class="price-row base-price">+ <span id="displayBase">10,000</span> 원</div>
            <div class="price-row discount-price">학생 할인 - <span id="displayStudentDiscount">0</span> 원</div>
            <div class="price-row discount-price">쿠폰 할인 - <span id="displayCouponDiscount">0</span> 원</div>
            <div class="total-line"></div>
            <div class="final-price"><span id="displayTotal">10,000</span> 원</div>
        </div>

        <div class="counter-bar">
            <button type="button" class="counter-btn" onclick="changeCount(-1)">−</button>
            <span>인원: <span id="peopleCount">1</span>명</span>
            <button type="button" class="counter-btn" onclick="changeCount(1)">+</button>
        </div>

        <form action="booking_process.php" method="POST" onsubmit="return validateBooking()" class="booking-form">
            <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
            <input type="hidden" name="time" id="selectedTime" value="">
            <input type="hidden" name="people" id="selectedPeople" value="1">
            <input type="hidden" name="price" id="selectedPrice" value="">
            
            <input type="hidden" name="coupon_id" id="selectedCouponId" value="">
            
            <button type="submit" class="btn-submit">예매하기</button>
        </form>
    </div>
</div>

<script>
    const basePricePerPerson = <?= $base_price ?>;
    const discountRate = <?= $discount_rate ?>;

    let currentPeople = 1;
    let currentTime = null;

    updatePrice();

    function selectTime(btn, timeStr) {
        const buttons = document.querySelectorAll('.time-btn');
        buttons.forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        currentTime = timeStr;
        document.getElementById('selectedTime').value = timeStr;
    }

    function changeCount(diff) {
        const newCount = currentPeople + diff;
        if (newCount < 1) return;
        if (newCount > 10) {
            alert("최대 10명까지 예매 가능합니다.");
            return;
        }
        currentPeople = newCount;
        document.getElementById('peopleCount').innerText = currentPeople;
        document.getElementById('selectedPeople').value = currentPeople;
        
        updatePrice();
    }

    // 가격 계산 함수
    function updatePrice() {
        const totalBase = basePricePerPerson * currentPeople;

        const studentDiscount = totalBase * discountRate;

        const couponSelect = document.getElementById('couponSelect');
        const selectedOption = couponSelect.options[couponSelect.selectedIndex];
        const couponDiscount = parseInt(selectedOption.value) || 0;
        
        const couponId = selectedOption.getAttribute('data-id') || "";
        document.getElementById('selectedCouponId').value = couponId;

        let finalPrice = totalBase - studentDiscount - couponDiscount;
        if (finalPrice < 0) finalPrice = 0;

        document.getElementById('displayBase').innerText = totalBase.toLocaleString();
        document.getElementById('displayStudentDiscount').innerText = studentDiscount.toLocaleString();
        document.getElementById('displayCouponDiscount').innerText = couponDiscount.toLocaleString();
        document.getElementById('displayTotal').innerText = finalPrice.toLocaleString();
        
        document.getElementById('selectedPrice').value = finalPrice;
    }

    function validateBooking() {
        if (!currentTime) {
            alert("영화 시간을 선택해주세요.");
            return false;
        }
        return confirm(currentTime + " 영화를 " + currentPeople + "명 예매하시겠습니까?");
    }
</script>

</body>
</html>