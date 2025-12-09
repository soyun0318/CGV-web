<?php
session_start();
$id = $_GET['id'];
$conn = new mysqli("localhost", "root", "", "sample");
$conn -> query("DELETE FROM board WHERE id = $id");
header("Location: board_list.php");
?>