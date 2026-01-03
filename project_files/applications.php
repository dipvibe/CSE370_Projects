<?php
include "auth.php";
include "connection.php";

if ($_SESSION['role'] !== 'worker') {
    header("Location: welcome.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT j.area, j.work_type, j.schedule, r.status
     FROM Job_Request r
     JOIN Job_List j ON r.job_id = j.job_id
     WHERE r.worker_id = ?
     ORDER BY j.area ASC"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include "navbar.php"; ?>

<h2>My Job Applications</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Area</th>
    <th>Work Type</th>
    <th>Schedule</th>
    <th>Status</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['area']) ?></td>
    <td><?= htmlspecialchars($row['work_type']) ?></td>
    <td><?= htmlspecialchars($row['schedule']) ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php endwhile; ?>
</table>
