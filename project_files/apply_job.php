<?php
include "auth.php";
include "connection.php";

if ($_SESSION['role'] !== 'worker') {
    header("Location: welcome.php");
    exit;
}

// Apply to job
if (isset($_POST['apply'])) {
    $job_id = $_POST['job_id'];

    $stmt = $conn->prepare(
        "INSERT IGNORE INTO Job_Request (job_id, worker_id)
         VALUES (?, ?)"
    );
    $stmt->bind_param("ii", $job_id, $_SESSION['user_id']);
    $stmt->execute();
}

// Search by area
$area = $_GET['area'] ?? '';

if ($area !== '') {
    $stmt = $conn->prepare(
        "SELECT job_id, area, work_type, schedule, salary_offer
         FROM Job_List
         WHERE area LIKE ?
         ORDER BY area ASC"
    );
    $like = "%$area%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $jobs = $stmt->get_result();
} else {
    $jobs = $conn->query(
        "SELECT job_id, area, work_type, schedule, salary_offer
         FROM Job_List
         ORDER BY area ASC"
    );
}
?>

<?php include "navbar.php"; ?>

<h2>Available Jobs</h2>

<form method="GET">
    <input type="text" name="area" placeholder="Search by area"
           value="<?= htmlspecialchars($area) ?>">
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="8">
<tr>
    <th>Area</
