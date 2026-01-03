<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary pt-3">
  <div class="container-fluid">

    <a class="navbar-brand" href="index.php">House Hold Network</a>

    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <li class="nav-item">
          <a class="nav-link active" href="welcome.php">Home</a>
        </li>
      <?php endif; ?>
    </ul>

    <div class="d-flex">
      <?php if (!isset($_SESSION['loggedin'])): ?>
        <a class="btn btn-outline-success mx-2" href="signup.php">Signup</a>
        <a class="btn btn-outline-primary mx-2" href="login.php">Login</a>
      <?php else: ?>
        <span class="navbar-text me-3">
          Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>
        <a class="btn btn-outline-danger mx-2" href="logout.php">Logout</a>
      <?php endif; ?>
    </div>

  </div>
</nav>

