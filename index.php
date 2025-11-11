<?php
include("./partials/_dbconnect.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include('./partials/_commonFiles.php'); ?>
<style>
  /* .card {
    padding: 0 !important;
    margin: 1rem;
  } */
</style>

<body>
  <?php include('./partials/_nav.php') ?>
  <div class="container mb-3">
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

    <div class="card mt-3">
      <h5 class="card-header">PHP</h5>
      <div class="card-body">
        <h5 class="card-title">Special title treatment</h5>
        <button class="btn btn-primary my-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
          aria-expanded="false" aria-controls="collapseExample">
          Read the blog
        </button>
        <div class="collapse" id="collapseExample">
          <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        </div>
      </div>
    </div>
    <?php

    $sql = "SELECT blog.*, users.username AS author_name 
            FROM blog 
            JOIN users ON blog.author_id = users.id";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
      $content = $row['content'];
      $short_content = substr($content, 0, 150); // show first 150 characters
      if (strlen($content) > 150) {
        $short_content .= '...';
      }

      echo '
  <div class="card mt-3">
    <h5 class="card-header">' . htmlspecialchars($row['title']) . '</h5>
    <div class="card-body">
      <p class="card-title">@' . htmlspecialchars($row['author_name']) . '</p>
      <p class="card-text">' . $short_content . '</p>
      <a href="./showblog.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Read More...</a>
    </div>
  </div>';
    }


    ?>
  </div>
  <style>
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