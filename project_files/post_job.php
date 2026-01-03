<?php
include "auth.php";
include "connection.php";

if ($_SESSION['role'] !== 'employer') {
    header("Location: welcome.php");
    exit;
}

$msg = "";

if (isset($_POST['submit'])) {
    $salary   = $_POST['salary'];
    $workType = $_POST['work_type'];
    $schedule = $_POST['schedule'];
    $area     = $_POST['area'];
    $houseNo  = $_POST['house_no'];

    $stmt = $conn->prepare(
        "INSERT INTO Job_List
        (employer_id, salary_offer, work_type, schedule, area, house_no)
        VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "idssss",
        $_SESSION['user_id'],
        $salary,
        $workType,
        $schedule,
        $area,
        $houseNo
    );

    if ($stmt->execute()) {
        $msg = "Job posted successfully!";
    }
}
?>

<?php include "navbar.php"; ?>

<h2>Post a Job</h2>

<?php if ($msg): ?><p style="color:green"><?= $msg ?></p><?php endif; ?>

<form method="POST">
    Salary: <input type="number" step="0.01" name="salary" required><br><br>
    Work Type: <input type="text" name="work_type" required><br><br>
    Schedule: <input type="text" name="schedule"><br><br>
    Area: <input type="text" name="area" required><br><br>
    House No: <input type="text" name="house_no"><br><br>

    <button type="submit" name="submit">Post Job</button>
</form>
