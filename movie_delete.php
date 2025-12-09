<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$id = $_GET['id'];
$sql = "DELETE FROM movie WHERE id=$id";
mysqli_query($conn, $sql);
header("Location: movie_admin_list.php");
?>