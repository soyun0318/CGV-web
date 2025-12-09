<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$id = $_GET['id'];
$sql = "DELETE FROM couponbox WHERE id=$id";
mysqli_query($conn, $sql);
header("Location: coupon_mine.php");
?>