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

        <?php if ($_SESSION['role'] === 'employer'): ?>
          <li class="nav-item"><a class="nav-link" href="post_job.php">Post Job</a></li>
          <li class="nav-item"><a class="nav-link" href="view_application.php">Applications</a></li>
          <li class="nav-item"><a class="nav-link" href="employer_payments.php">Payments</a></li>
        <?php elseif ($_SESSION['role'] === 'worker'): ?>
          <li class="nav-item"><a class="nav-link" href="apply_job.php">Find Jobs</a></li>
          <li class="nav-item"><a class="nav-link" href="applications.php">My Applications</a></li>
          <li class="nav-item"><a class="nav-link" href="worker_payments.php">My Payments</a></li>
        <?php endif; ?>

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

