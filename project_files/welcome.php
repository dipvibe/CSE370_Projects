<?php include "auth.php"; ?>
<?php include "navbar.php"; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Welcome</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="form">
  <h1>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
  <p>Your role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>
</div>

</body>
</html>

