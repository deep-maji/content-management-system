<?php
session_start();

if (!isset($_SESSION['id'])) {
  $_SESSION['flash_message'] = "You are not logged in!";
  $_SESSION['flash_message_type'] = "danger";
  header("location:index.php");
  exit;
}

include("./partials/_dbconnect.php");

$id = (int) $_GET["id"];
$author_id = (int) $_SESSION['id'];

// Perform delete query
$sql = "DELETE FROM `blog` WHERE `id` = $id AND `author_id` = $author_id";
$result = mysqli_query($conn, $sql);

if ($result) {
  if (mysqli_affected_rows($conn) > 0) {
    // Post successfully deleted
    $_SESSION['flash_message'] = "Post deleted successfully!";
    $_SESSION['flash_message_type'] = "success";

    if (isset($_SERVER['HTTP_REFERER'])) {
      header("location:" . $_SERVER['HTTP_REFERER']);
    } else {
      header("location:index.php");
    }
  } else {
    // Post not found or not owned by user
    $_SESSION['flash_message'] = "You are not the owner of this post!";
    $_SESSION['flash_message_type'] = "danger";
    header("location:index.php");
  }
} else {
  // Query failed
  $_SESSION['flash_message'] = "Something went wrong while deleting the post!";
  $_SESSION['flash_message_type'] = "danger";
  header("location:showBlog.php?id=" . $id);
}

exit;
?>
