<?php

session_start();

if (!isset($_SESSION['id'])) {
  header("location:index.php");
  $_SESSION['flash_message'] = "You are not loged in!";
  $_SESSION['flash_message_type'] = "danger";
  exit;
}

include("./partials/_dbconnect.php");

$id = (int) $_GET["id"];
$author_id = $_SESSION['id'];


$sql = "DELETE from `blog` where `id`=$id AND author_id = $author_id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("location:" . $_SERVER['HTTP_REFERER']);
  // header("location:index.php");
} else {
  header("location:showBlog.php?id=" . $id);
  $_SESSION['flash_message'] = "You are not Owner!";
  $_SESSION['flash_message_type'] = "danger";
}

?>