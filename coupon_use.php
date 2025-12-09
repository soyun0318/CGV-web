<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$id = $_GET['id'];
$sql = "UPDATE couponbox SET is_used=1, used_date=CURDATE() WHERE id = $id";
mysqli_query($conn, $sql);
header("Location: coupon_view.php?id=$id");
?>