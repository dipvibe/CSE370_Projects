<?php
session_start();
include "connection.php";

// If already logged in, go to welcome
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: welcome.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Escape the email to prevent SQL injection
    $safe_email = mysqli_real_escape_string($conn, $email);

    // 2. Create the query string
    $sql = "SELECT user_id, name, password, role FROM General_User WHERE email = '$safe_email'";
    
    // 3. Run the query
    $result = mysqli_query($conn, $sql);

    // 4. Check if user exists
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 5. Verify password
        if (password_verify($password, $row['password'])) {
            // Login success
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['LAST_ACTIVITY'] = time();

            header("Location: welcome.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<?php include "navbar.php"; ?>

<div id="form">
  <h1>Login</h1>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <br><br>
    <input type="submit" id="btn" name="login" value="Login">
  </form>
  
  <div class="mt-3 text-center">
      <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
