<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$sql = "SELECT *,
        CASE
            WHEN is_univ=1 AND is_swu=1 THEN '20%'
            WHEN is_univ=1 OR is_swu=1 THEN '10%'
            ELSE '0%'
        END AS discount
        FROM cgvtable";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원 목록</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Malgun Gothic', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px 0;
        }

        .app-container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 { font-size: 24px; font-weight: bold; }
        .header span { font-size: 14px; opacity: 0.9; font-weight: 500; }

        .content-container {
            padding: 24px;
            overflow-x: auto;
        }

        .page-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
            border-left: 5px solid #dc2626;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 700;
            padding: 12px;
            font-size: 14px;
            border-bottom: 2px solid #dc2626;
            text-align: center;
            white-space: nowrap;
        }

        td {
            padding: 12px;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            background-color: white;
        }

        tr:last-child td { border-bottom: none; }
        tr:nth-child(even) td { background-color: #fef2f2; }
        tr:hover td { background-color: #fff1f2; }

        .discount-badge {
            display: inline-block;
            color: white;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* 20% 할인 */
        .badge-20 {
            background-color: #dc2626; 
        }

        /* 10% 할인 */
        .badge-10 {
            background-color: #f78787; 
        }

        /* 0% 할인 */
        .badge-0 {
            background-color: #9ca3af; 
        }

        .note {
            font-size: 13px;
            color: #6b7280;
            margin-top: 16px;
            text-align: right;
        }

        .status-yes { color: #dc2626; font-weight: bold; }
        .status-no { color: #9ca3af; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }
    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <a href="admin_page.php" class="btn-back">닫기</a>
        </div>

        <div class="content-container">
            <h2 class="page-title">회원 목록 조회</h2>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>이메일</th>
                        <th>주소</th>
                        <th>대학생</th>
                        <th>SWU</th>
                        <th>할인율</th>
                        <th>가입일시</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) { 
                        while ($row = $result->fetch_assoc()) {
                            $discountRate = $row["discount"];
                            $badgeColorClass = "";

                            if ($discountRate == '20%') {
                                $badgeColorClass = "badge-20"; 
                            } elseif ($discountRate == '10%') {
                                $badgeColorClass = "badge-10"; 
                            } else {
                                $badgeColorClass = "badge-0";  
                            }
                            
                            echo "<tr>";
                            echo "<td>" . $row["no"] . "</td>";
                            echo "<td><strong>" . $row["userid"] . "</strong></td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["address"] . "</td>";
                            
                            echo "<td><span class='" . ($row["is_univ"] ? "status-yes" : "status-no") . "'>" . ($row["is_univ"] ? "예" : "아니오") . "</span></td>";
                            echo "<td><span class='" . ($row["is_swu"] ? "status-yes" : "status-no") . "'>" . ($row["is_swu"] ? "예" : "아니오") . "</span></td>";
                            
                            echo "<td><span class='discount-badge " . $badgeColorClass . "'>" . $row["discount"] . "</span></td>";
                            echo "<td>" . $row["regdate"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='padding: 40px 0; color: #999;'>등록된 회원 정보가 없습니다.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <p class="note">※ 할인율 정책: 대학생(10%) + 서울여대 학생(10%) = 최대 20%</p>
        </div>
    </div>

</body>
</html>

<?php
$conn->close(); 
?>