<?php

include("./partials/_dbconnect.php");
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('./partials/_commonFiles.php'); ?>
</head>

<body>
  <?php include('./partials/_nav.php') ?>
  <?php
  if (isset($_SESSION['flash_message'])) {
    echo '<div class="mt-3 alert alert-' . $_SESSION['flash_message_type'] . ' alert-dismissible fade show" role="alert">
              ' . $_SESSION['flash_message'] . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_message_type']);
  }
  ?>
  <div class="container-fluid mt-3">
    <?php
    // Assuming connection already exists
    $id = (int) $_GET["id"]; // Prevent SQL injection
    
    $sql = "SELECT blog.*, users.username AS author_name 
        FROM blog 
        JOIN users ON blog.author_id = users.id 
        WHERE blog.id = $id";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
      echo '
    <h4>' . htmlspecialchars($row['title']) . '</h4>
    <p class="text-muted small mb-2"><b>Author</b>: <i>@' . htmlspecialchars($row['author_name']) . '</i></p>
    <p class="text-muted small mb-2">
      <b>Created on:</b> ' . date("F j, Y", strtotime($row['created_at'])) . '<br>
      <b>Last updated:</b> ' . date("F j, Y", strtotime($row['update_at'])) . '
    </p>';

      // If logged-in user is the author → show edit/delete options
      if (isset($_SESSION['id']) && $_SESSION['id'] == $row['author_id']) {
        echo '
    <a href="./editBlog.php?id=' . $row['id'] . '" class="btn btn-primary">Edit</a>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row['id'] . '">Delete Post</button>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal' . $row['id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['id'] . '" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="deleteModalLabel' . $row['id'] . '">Confirm Deletion</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            Are you sure you want to delete <strong>' . htmlspecialchars($row['title']) . '</strong>?
          </div>
          <div class="modal-footer justify-content-center border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a href="./deleteProcess.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>';
      }

      echo '<hr><div class="content">' . $row['content'] . '</div>';
    } else {
      echo '<p>Blog not found.</p>';
    }
    ?>

  </div>
  <style>
    .content {
      padding-bottom: 1rem;
    }

    .ql-code-block-container {
      background-color: black !important;
      color: #fff !important;
      padding: 1rem;
      border-radius: .6rem;
    }
  </style>
  <script>
    const html = document.documentElement;
    const themeRadios = document.querySelectorAll('input[name="themeRadios"]');

    // --- Helper: Get cookie by name ---
    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? match[2] : null;
    }

    // --- Step 1: Apply theme from PHP cookie on load ---
    const savedTheme = getCookie('_theme') || 'dark'; // default fallback
    html.setAttribute('data-bs-theme', savedTheme);

    // Check the correct radio button
    const activeRadio = document.querySelector(`input[name="themeRadios"][value="${savedTheme}"]`);
    if (activeRadio) activeRadio.checked = true;

    // --- Step 2: When user switches theme ---
    themeRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        if (radio.checked) {
          const themeValue = radio.value;

          // Apply immediately
          html.setAttribute('data-bs-theme', themeValue);

          // Update cookie (valid for 7 days)
          document.cookie = `_theme=${themeValue}; path=/; max-age=${60 * 60 * 24 * 7}`;
        }
      });
    });
  </script>
</body>

</html>