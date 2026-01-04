<?php
include "auth.php";
include "connection.php";

// Only employers can post jobs
if ($_SESSION['role'] !== 'employer') {
    header("Location: welcome.php");
    exit;
}

$msg = "";

if (isset($_POST['submit'])) {
    $salary   = mysqli_real_escape_string($conn, $_POST['salary']);
    $workType = mysqli_real_escape_string($conn, $_POST['work_type']);
    $schedule = mysqli_real_escape_string($conn, $_POST['schedule']);
    $area     = mysqli_real_escape_string($conn, $_POST['area']);
    $houseNo  = mysqli_real_escape_string($conn, $_POST['house_no']);
    $employer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO Job_List (employer_id, salary_offer, work_type, schedule, area, house_no) 
            VALUES ('$employer_id', '$salary', '$workType', '$schedule', '$area', '$houseNo')";

    if (mysqli_query($conn, $sql)) {
        $msg = "Job posted successfully!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post a Job</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Post a New Job</h3>
                </div>
                <div class="card-body">
                    <?php if ($msg): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Salary Offer</label>
                            <input type="number" step="0.01" name="salary" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Work Type</label>
                            <select name="work_type" class="form-select" required>
                                <option value="">Select Work Type</option>
                                <option value="House Helper / Maid">House Helper / Maid</option>
                                <option value="Cook / Home Chef">Cook / Home Chef</option>
                                <option value="Driver">Driver</option>
                                <option value="Gardener / Mali">Gardener / Mali</option>
                                <option value="Babysitter / Nanny">Babysitter / Nanny</option>
                                <option value="Elderly Caretaker">Elderly Caretaker</option>
                                <option value="Home Nurse (Basic Care)">Home Nurse (Basic Care)</option>
                                <option value="Cleaner (Deep Cleaning)">Cleaner (Deep Cleaning)</option>
                                <option value="Electrician">Electrician</option>
                                <option value="Plumber">Plumber</option>
                                <option value="AC / Appliance Technician">AC / Appliance Technician</option>
                                <option value="Laundry & Iron Service">Laundry & Iron Service</option>
                                <option value="Security Guard">Security Guard</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Schedule</label>
                            <input type="text" name="schedule" class="form-control" placeholder="e.g. 9 AM - 5 PM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Area</label>
                            <input type="text" name="area" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">House No / Address</label>
                            <input type="text" name="house_no" class="form-control">
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary w-100">Post Job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
