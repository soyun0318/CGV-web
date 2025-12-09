<?php
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$name = $_POST['name'];
$director = $_POST['director'];
$description = $_POST['description'];

$upload_dir = 'images/'; 
$img_file = "";

if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
    $tmp_name = $_FILES['img']['tmp_name'];
    $original_name = $_FILES['img']['name'];
    
    $ext = pathinfo($original_name, PATHINFO_EXTENSION); 
    $new_filename = time() . "_" . $original_name; 
    
    $upload_path = $upload_dir . $new_filename;

    if (move_uploaded_file($tmp_name, $upload_path)) {
        $img_file = $new_filename; 
    } else {
        echo "<script>alert('이미지 업로드 실패'); history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('이미지 파일을 선택해주세요.'); history.back();</script>";
    exit;
}

$sql = "INSERT INTO movie (name, director, description, img) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $director, $description, $img_file);

if ($stmt->execute()) {
    echo "<script>
        alert('영화가 성공적으로 등록되었습니다.');
        location.href = 'admin_page.php'; 
    </script>";
} else {
    echo "<script>
        alert('영화 등록 실패: " . $conn->error . "');
        history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>