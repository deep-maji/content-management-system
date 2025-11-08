<header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary" style="border-bottom: 1px solid black">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">Blogger</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
        <div class="navbar-nav">
<!-- <a class="nav-link" href="./Logout.php">Logout</a> -->
          <?php
          if (isset($_SESSION['username'])) {
            echo '<a href="./createaBlog.php" class="nav-link">Create a Blog</a>
            
            <a class="nav-link" href="./welcome.php">Profile</a>';
          } else {
            echo '<li class="nav-item d-flex pt-2">
          <div class="mx-2">
            <input class="form-check-input" type="radio" name="themeRadios" id="lightMode" value="light"
              checked>
            <label class="form-check-label" for="lightMode">Light</label>
          </div>

          <div class="mx-2">
            <input class="form-check-input" type="radio" name="themeRadios" id="darkMode" value="dark">
            <label class="form-check-label" for="darkMode">Dark</label>
          </div>
        </li>
        <a href="./createaBlog.php" class="nav-link">Create a Blog</a>
            <a class="nav-link" href="./signup.php">SignIn</a>
                  <a class="nav-link" href="./login.php">Login</a>';
          }
          ?>

        </div>
      </div>
    </div>
  </nav>
</header>