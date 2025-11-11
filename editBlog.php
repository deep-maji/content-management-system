<?php
session_start();

if (!isset($_SESSION['id'])) {
  $_SESSION['flash_message'] = "You are not logged in!";
  $_SESSION['flash_message_type'] = "danger";
  header("location:index.php");
  exit;
}

include("./partials/_dbconnect.php");

// Handle POST request (when user submits form)
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
  $content = $_POST["content"];
  $content = mysqli_real_escape_string($conn, $content);
  $id = intval($_GET["id"]);
  $author_id = intval($_SESSION['id']);
  $updated_at = date('Y-m-d');

  // Update the blog post and set update_at to current date
  $sql = "UPDATE `blog` 
          SET `title` = '$title', 
              `content` = '$content',
              `update_at` = NOW()
          WHERE `id` = $id AND `author_id` = $author_id";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['flash_message'] = "Post updated successfully!";
    $_SESSION['flash_message_type'] = "success";
    header("location:showBlog.php?id=" . $id);
    exit;
  } else {
    $_SESSION['flash_message'] = "Failed to update post: " . mysqli_error($conn);
    $_SESSION['flash_message_type'] = "danger";
    header("location:editBlog.php?id=" . $id);
    exit;
  }
}

// Fetch blog data for the form
$id = intval($_GET["id"]);
$author_id = intval($_SESSION['id']);

$sql = "SELECT * FROM `blog` WHERE `id` = $id AND `author_id` = $author_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
  $_SESSION['flash_message'] = "You are not the owner or post not found!";
  $_SESSION['flash_message_type'] = "danger";
  header("location:index.php");
  exit;
}

$row = mysqli_fetch_assoc($result);
$title = htmlspecialchars($row["title"]);
$content = $row["content"];
$author = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('./partials/_commonFiles.php'); ?>
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
</head>

<body>
  <?php include('./partials/_nav.php'); ?>

  <div class="container mt-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title mb-4 text-center">Update Your Blog</h3>
        <form action="./editBlog.php?id=<?= $id ?>" method="post" id="blogForm" novalidate class="needs-validation">
          <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input readonly value="<?= $author ?>" type="text" class="form-control" id="author">
          </div>

          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input value="<?= $title ?>" type="text" name="title" class="form-control" id="title" required>
          </div>

          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <div id="editor"><?= $content ?></div>
            <input type="hidden" name="content" id="hiddenContent">
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include("./partials/footer.html") ?>
  <script src="js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

  <script>
    const quill = new Quill("#editor", {
      theme: "snow"
    });

    document.querySelector("#blogForm").addEventListener("submit", (e) => {
      const editorContent = document.querySelector(".ql-editor").innerHTML;
      document.querySelector("#hiddenContent").value = editorContent;
    });
  </script>

  <!-- Theme toggle -->
  <script>
    const html = document.documentElement;
    const themeRadios = document.querySelectorAll('input[name="themeRadios"]');

    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? match[2] : null;
    }

    const savedTheme = getCookie('_theme') || 'dark';
    html.setAttribute('data-bs-theme', savedTheme);

    const activeRadio = document.querySelector(`input[name="themeRadios"][value="${savedTheme}"]`);
    if (activeRadio) activeRadio.checked = true;

    themeRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        if (radio.checked) {
          const themeValue = radio.value;
          html.setAttribute('data-bs-theme', themeValue);
          document.cookie = `_theme=${themeValue}; path=/; max-age=${60 * 60 * 24 * 7}`;
        }
      });
    });
  </script>
</body>

</html>