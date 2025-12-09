<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$id = $_GET['id'];
$sql = "UPDATE couponbox SET likes = likes + 1 WHERE id = $id";
mysqli_query($conn, $sql);
header("Location: coupon_view.php?id=$id");
?>
