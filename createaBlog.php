<?php

session_start();

if (!isset($_SESSION['username'])) {
  $_SESSION["flash_message"] = "User is not logged in!";
  $_SESSION['flash_message_type'] = "danger";
  header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  include("./partials/_dbconnect.php");

  $author = filter_input(INPUT_POST, "author", FILTER_SANITIZE_SPECIAL_CHARS);
  $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
  $content = $_POST["content"];
  $content = mysqli_real_escape_string($conn, $content);
  $id = $_SESSION['id'];

$sql = "INSERT INTO `blog` (`author_id`, `title`, `content`, `created_at`, `update_at`) 
        VALUES ('$id', '$title', '$content', NOW(), NOW())";
        
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Data Inserted Successfully";
    header("location:index.php");
  } else {
    echo "Error description: " . mysqli_error($conn);
    header("location:createaBlog.php");
    exit();
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include('./partials/_commonFiles.php'); ?>
<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

<body>
  <?php include('./partials/_nav.php') ?>
  <div class="container-fluid mt-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title mb-4 text-center">Create a Post</h3>
        <form action="./createaBlog.php" method="post" id="blogForm" novalidate class="needs-validation">
          <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input readonly value=<?php echo $_SESSION['username']; ?> type="text" class="form-control" name="author"
              id="author" placeholder="Enter author name" required>
          </div>

          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" placeholder="Enter post title" required>
          </div>

          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <!-- Create the editor container -->
            <div id="editor">
              <p>Hello World!</p>
              <p>Some initial <strong>bold</strong> text</p>
            </div>
            <input type="hidden" name="content" id="hiddenContent">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Submit</button>
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