<?php

session_start();
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != true) {
  header("location:login.php");
  exit();
}

if (!isset($_COOKIE['_theme'])) {
  setcookie("_theme", "light", time() + (86400 * 7), "/");
}

include "./partials/_dbconnect.php";
$showAlert = false;
$showError = false;
$phoneLenError = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  if (isset($_POST['phone'])) {
    if (strlen($_POST['phone']) == 10) {
      $id = $_SESSION['id'];
      $phone = !empty($_POST['phone']) ? $_POST['phone'] : 'NULL';
      $phonesql = "UPDATE `users` SET `phone` = $phone WHERE id = '$id'";
      $result = mysqli_query($conn, $phonesql);
      if ($result) {
        $showAlert = "Phone number has been updated successfully!";
        $_SESSION['phone'] = $phone;
        // header("location:welcome.php");
      }
    } else {
      $phoneLenError = "Phone number must be 10 digits long.";
    }
  } else {
    if (($_SESSION['username'] != $_POST['username']) || $_SESSION['email'] != $_POST['email']) {
      $username = $_POST['username'];
      $email = $_POST['email'];

      $existsSql = "SELECT `username` FROM `users` WHERE `username` = '$username'";
      $userName_existResult = mysqli_query($conn, $existsSql);
      $num_of_rows_username = mysqli_num_rows($userName_existResult);

      $existsSql = "SELECT `email` FROM `users` WHERE `email` = '$email'";
      $email_existResult = mysqli_query($conn, $existsSql);
      $num_of_rows_email = mysqli_num_rows($email_existResult);
      if ($num_of_rows_username > 0 && $num_of_rows_email > 0) {
        $showError = "Username or email already exists.";
      } else {
        $id = $_SESSION['id'];
        $sql = "UPDATE `users` SET `username` = '$username', `email` = '$email' WHERE id = '$id';";
        $result = mysqli_query($conn, $sql);
        if ($result) {
          $showAlert = "Your profile has been updated successfully!";
          $_SESSION['isLogin'] = true;
          $_SESSION['username'] = $username;
          $_SESSION['email'] = $email;
          // header("location:welcome.php");
        }
      }
    }
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - <?= $_SESSION['username']; ?></title>
  <?php include('./partials/_commonFiles.php'); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    #phone::-webkit-outer-spin-button,
    #phone::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<body>
  <?php require './partials/_nav.php' ?>
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
  <?php
  if ($showAlert) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-success alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $showAlert . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }

  if ($showError) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-danger alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $showError . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }

  if ($phoneLenError) {
    echo '<div class="d-flex justify-content-center mt-3"><div class="alert alert-danger alert-dismissible fade show col-12 col-sm-12 col-md-5" role="alert">
    ' . $phoneLenError . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div></div>';
  }
  ?>
  <div class="container-fluid my-3">
    <h4>Welcome @<?= $_SESSION['username']; ?></h4>
    <div class="card shadow-sm p-3">
      <div>
        <div class="mb-3" style="cursor: pointer;"><strong>Apperance</strong></div>
        <form>
          <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Theme</legend>
            <div class="col-sm-10">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="themeRadios" id="lightMode" value="light" checked>
                <label class="form-check-label" for="lightMode">Light</label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="themeRadios" id="darkMode" value="dark">
                <label class="form-check-label" for="darkMode">Dark</label>
              </div>
            </div>
          </fieldset>
        </form>
      </div>

      <div>
        <div class="mb-3"><strong>Profile</strong></div>
        <div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
              <input readonly value="<?= $_SESSION['username'] ?>" type="text" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input readonly value="<?= $_SESSION['email'] ?>" type="email" class="form-control">
            </div>
          </div>




          <button class="my-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#updateProfile"
            aria-expanded="false" aria-controls="collapseExample">
            Edit Profile
          </button>
          <div class="collapse" id="updateProfile">
            <div class="my-3"><strong>Update your profile</strong></div>
            <form action="./welcome.php" method="post" class="needs-validation" novalidate>
              <div class="row mb-3">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['username'] ?>" type="text" name="username" class="form-control"
                    id="username" required>
                  <div class="invalid-feedback">username should not be empty</div>
                </div>
              </div>

              <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input value="<?= $_SESSION['email'] ?>" type="email" name="email" class="form-control" id="email"
                    required>
                  <div class="invalid-feedback">Email should not be empty</div>
                </div>
              </div>

              <button type="submit" class="mb-3 btn btn-primary">Save</button>
            </form>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
              <?php
              // echo $_SESSION['phone'];
              if ($_SESSION['phone'] != 'NULL') {
                echo '<div class="d-flex">
                        <input readonly value="' . $_SESSION["phone"] . '" type="number" class="form-control">
                        <button class="ms-3 btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addphone"
                          aria-expanded="false">
                          Update
                        </button>
                      </div>
                      ';
              } else {
                echo '<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addphone"
                        aria-expanded="false">
                        Add
                      </button>';
              }
              ?>

              <div class="collapse mt-3" id="addphone">
                <form action="./welcome.php" method="post" class="needs-validation" novalidate>

                  <div class="row mb-3">
                    <div class="col-sm-10 d-flex">
                      <input value="<?= $_SESSION['phone'] ?>" type="number" name="phone" class="form-control"
                        id="phone" required>
                      <!-- <div class="invalid-feedback">Phone number should 10 digits</div> -->
                      <button type="submit" class="mx-3 btn btn-primary">Save</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
          <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
            Logout
          </a>

          <!-- Logout Confirmation Modal -->
          <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                  <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  Are you sure you want to logout from your account?
                </div>
                <div class="modal-footer justify-content-center border-0">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <a href="./Logout.php" class="btn btn-danger">Logout</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm p-4 mt-4">
      <h4 class="mb-3">All Posts</h4>
      <div class="row g-3">
        <?php
        $author_id = $_SESSION['id']; // current logged-in user ID
        $sql = "SELECT * FROM blog WHERE author_id = $author_id ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $content = $row['content'];
            $short_content = mb_substr($content, 0, 80);
            if (mb_strlen($content) > 80) {
              $short_content .= '...';
            }

            echo '
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-primary mb-2">' . htmlspecialchars($row['title']) . '</h5>
              <p class="card-text text-muted flex-grow-1">' . $short_content . '</p>
              <div class="mt-3 d-flex flex-wrap gap-2">
                <a href="./editBlog.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="./showBlog.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary">Read More</a>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row['id'] . '">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal' . $row['id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['id'] . '" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
              <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel' . $row['id'] . '">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                Are you sure you want to delete <strong>' . htmlspecialchars($row['title']) . '</strong>?
              </div>
              <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="./deleteProcess.php?id=' . $row['id'] . '&author_id=' . $row['author_id'] . '" class="btn btn-danger">Delete</a>
              </div>
            </div>
          </div>
        </div>
        ';
          }
        } else {
          echo '<p class="text-muted">No blog posts found.</p>';
        }
        ?>
      </div>
    </div>

  </div>
  <?php include("./partials/footer.html") ?>
  <script src="js/script.js"></script>
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