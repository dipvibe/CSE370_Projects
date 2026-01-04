<?php
include "auth.php";
include "connection.php";

// Only workers can apply for jobs
if ($_SESSION['role'] !== 'worker') {
    header("Location: welcome.php");
    exit;
}

$msg = "";
$error = "";

// Apply to job
if (isset($_POST['apply'])) {
    $job_id = $_POST['job_id'];
    $worker_id = $_SESSION['user_id'];

    // Check if already applied
    $check_sql = "SELECT * FROM Job_Request WHERE job_id = '$job_id' AND worker_id = '$worker_id'";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        $error = "You have already applied for this job.";
    } else {
        $sql = "INSERT INTO Job_Request (job_id, worker_id) VALUES ('$job_id', '$worker_id')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Application sent successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Search by area
$area = isset($_GET['area']) ? mysqli_real_escape_string($conn, $_GET['area']) : '';

if ($area !== '') {
    $sql = "SELECT * FROM Job_List WHERE area LIKE '%$area%' ORDER BY area ASC";
} else {
    $sql = "SELECT * FROM Job_List ORDER BY area ASC";
}

$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Available Jobs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Available Jobs</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <input type="text" name="area" class="form-control me-2" placeholder="Search by area..." value="<?php echo htmlspecialchars($area); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['work_type']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['area']); ?></h6>
                            <p class="card-text">
                                <strong>Salary:</strong> <?php echo htmlspecialchars($row['salary_offer']); ?> BDT<br>
                                <strong>Schedule:</strong> <?php echo htmlspecialchars($row['schedule']); ?><br>
                                <strong>Address:</strong> <?php echo htmlspecialchars($row['house_no']); ?>
                            </p>
                            <form method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $row['job_id']; ?>">
                                <button type="submit" name="apply" class="btn btn-success w-100">Apply Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No jobs found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
