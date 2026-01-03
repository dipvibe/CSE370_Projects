<?php
session_start();
include "connection.php";

// If already logged in, go to welcome
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: welcome.php");
    exit;
}

$error = "";

if (isset($_POST['submit'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $address  = trim($_POST['address']);
    $password = $_POST['password'];
    $cpass    = $_POST['cpassword'];
    $role     = $_POST['role'];

    if ($password !== $cpass) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT user_id FROM General_User WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Email already exists. Try logging in.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert into General_User
            $stmt = $conn->prepare(
                "INSERT INTO General_User (name, email, address, password, role)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $name, $email, $address, $hash, $role);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                // Insert into role-specific table
                if ($role === 'worker') {
                    $w = $conn->prepare("INSERT INTO Worker (user_id, experience, availability) VALUES (?, 0, 'available')");
                    $w->bind_param("i", $user_id);
                    $w->execute();
                } elseif ($role === 'employer') {
                    $e = $conn->prepare("INSERT INTO Employer (user_id) VALUES (?)");
                    $e->bind_param("i", $user_id);
                    $e->execute();
                } elseif ($role === 'administrator') {
                    $a = $conn->prepare("INSERT INTO Administrator (user_id, responsibility) VALUES (?, '')");
                    $a->bind_param("i", $user_id);
                    $a->execute();
                }

                header("Location: login.php");
                exit;
            } else {
                $error = "Signup failed. Please try again.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div id="form">
  <h1>Signup</h1>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Address</label>
    <input type="text" name="address">

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="cpassword" required>

    <label>Role</label>
    <select name="role" class="form-select" required>
      <option value="">Select Role</option>
      <option value="employer">Employer</option>
      <option value="worker">Worker</option>
      <option value="administrator">Administrator</option>
    </select>

    <input type="submit" id="btn" name="submit" value="Sign Up">
  </form>

  <p class="text-center mt-3">
    Already have an account? <a href="login.php">Login</a>
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
