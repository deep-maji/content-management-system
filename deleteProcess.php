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
  if (isset($_SERVER['HTTP_REFERER'])) {
    header("location:" . $_SERVER['HTTP_REFERER']);
    $_SESSION['flash_message'] = "Post deleted sucessfully!";
    $_SESSION['flash_message_type'] = "danger";
    exit;
  }
  header("location:index.php");
  $_SESSION['flash_message'] = "You are not Owner!";
  $_SESSION['flash_message_type'] = "danger";
} else {
  header("location:showBlog.php?id=" . $id);
  $_SESSION['flash_message'] = "Something worng";
  $_SESSION['flash_message_type'] = "danger";
}

?>