<?php

session_start();

if (!isset($_SESSION['id'])) {
  header("location:index.php");
  $_SESSION['flash_message'] = "You are not loged in!";
  $_SESSION['flash_message_type'] = "danger";
  exit;
}

if (isset($_SESSION['id']) && ($_SESSION['id'] != $_GET['author_id'])) {
  header("location:index.php");
  $_SESSION['flash_message'] = "You are not Owner!";
  $_SESSION['flash_message_type'] = "danger";
  exit;
}

include("./partials/_dbconnect.php");

$id = (int) $_GET["id"];
$author_id = (int) $_GET["author_id"];

$sql = "DELETE from `blog` where `id`=$id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("location:" . $_SERVER['HTTP_REFERER']);
} else {
  header("location:showBlog.php?id=" . $id . "&author_id=" . $author_id);
}

?>