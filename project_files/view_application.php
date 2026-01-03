<?php
include "auth.php";
include "connection.php";

if ($_SESSION['role'] !== 'employer') {
    header("Location: welcome.php");
    exit;
}


if (isset($_POST['hire'])) {
    $request_id = $_POST['request_id'];
    $job_id     = $_POST['job_id'];
    $worker_id  = $_POST['worker_id'];
    $employer_id = $_SESSION['user_id'];
    $joining_date = date('Y-m-d');

    // 1️⃣ Check if job already has a hire
    $check = $conn->prepare(
        "SELECT hire_id FROM Hires WHERE job_id = ?"
    );
    $check->bind_param("i", $job_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows === 0) {

        // 2️⃣ Insert into Hires table
        $hire = $conn->prepare(
            "INSERT INTO Hires (worker_id, job_id, employer_id, joining_date)
             VALUES (?, ?, ?, ?)"
        );
        $hire->bind_param(
            "iiis",
            $worker_id,
            $job_id,
            $employer_id,
            $joining_date
        );
        $hire->execute();

        // 3️⃣ Accept the hired worker
        $accept = $conn->prepare(
            "UPDATE Job_Request
             SET status = 'accepted'
             WHERE request_id = ?"
        );
        $accept->bind_param("i", $request_id);
        $accept->execute();

        // 4️⃣ Reject all other applications for the same job
        $rejectOthers = $conn->prepare(
            "UPDATE Job_Request
             SET status = 'rejected'
             WHERE job_id = ? AND worker_id != ?"
        );
        $rejectOthers->bind_param("ii", $job_id, $worker_id);
        $rejectOthers->execute();
    }
}


$stmt = $conn->prepare(
    "SELECT 
        r.request_id,
        r.status,
        r.worker_id,
        j.job_id,
        j.area,
        j.work_type,
        g.name AS worker_name
     FROM Job_Request r
     JOIN Job_List j ON r.job_id = j.job_id
     JOIN General_User g ON r.worker_id = g.user_id
     WHERE j.employer_id = ?
     ORDER BY j.area ASC"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include "navbar.php"; ?>

<h2>Applications to My Jobs</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Worker</th>
    <th>Area</th>
    <th>Work Type</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['worker_name']) ?></td>
    <td><?= htmlspecialchars($row['area']) ?></td>
    <td><?= htmlspecialchars($row['work_type']) ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
    <td>
        <?php if ($row['status'] === 'pending'): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                <input type="hidden" name="job_id" value="<?= $row['job_id'] ?>">
                <input type="hidden" name="worker_id" value="<?= $row['worker_id'] ?>">
                <button type="submit" name="hire">Hire</button>
            </form>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>

