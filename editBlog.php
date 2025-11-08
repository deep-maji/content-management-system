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
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $author = filter_input(INPUT_POST, "author", FILTER_SANITIZE_SPECIAL_CHARS);
  $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
  $content = $_POST["content"];
  $content = mysqli_real_escape_string($conn, $content);
  $id = (int) $_GET["id"];
  $author_id = (int) $_GET["author_id"];

  $id = $_GET["id"];
  $sql = "UPDATE `blog` 
        SET `title`='$title',
            `content`='$content'
        WHERE `id` = $id";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("location:showBlog.php?id=" . $id . "&author_id=" . $author_id);
  } else {
    header("location:editBlog.php?id=" . $id . "&author_id=" . $author_id);
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('./partials/_commonFiles.php'); ?>
  <!-- Include stylesheet -->
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
</head>

<body>
  <?php include('./partials/_nav.php') ?>
  <?php

  $sql = "SELECT * from blog where id = " . $_GET["id"];
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  $id = $row['id'];
  $title = $row["title"];
  $author = $_SESSION['username'];
  $content = $row["content"];

  ?>
  <div class="container-fluid mt-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title mb-4 text-center">Update Your Blog</h3>
        <form action="./editBlog.php?id=<?= $id; ?>&author_id= <?= $row['author_id'] ?>" method="post" id="blogForm" novalidate class="needs-validation">
          <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input readonly value="<?php echo $author; ?>" type="text" class="form-control" name="author" id="author"
              placeholder="Enter author name" required>
          </div>

          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input value="<?php echo $title ?>" type="text" name="title" class="form-control" id="title"
              placeholder="Enter post title" required>
          </div>

          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <!-- Create the editor container -->
            <div id="editor">
              <?php echo $content; ?>
            </div>
            <input type="hidden" name="content" id="hiddenContent">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <script src="js/script.js"></script>

  <!-- Include the Quill library -->
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

  <!-- Initialize Quill editor -->
  <script>
    const quill = new Quill("#editor", {
      theme: "snow",
    });
    document.querySelector("#blogForm").addEventListener("submit", (e) => {
      let editorContent = document.querySelector(".ql-editor").innerHTML;
      document.querySelector("#hiddenContent").value = editorContent;
    })
  </script>
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