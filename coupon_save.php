<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$userid = $_POST['userid'];
$title = $_POST['title'];
$price = $_POST['price'];
$sql = "INSERT INTO couponbox (userid, title, price) VALUES ('$userid', '$title', '$price')";
mysqli_query($conn, $sql);
header("Location: coupon_list.php");
?>
