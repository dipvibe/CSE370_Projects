<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>House Hold Network</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 
include 'connection.php'; 
include 'navbar.php'; 

// Fetch Statistics
$worker_count = 0;
$employer_count = 0;
$jobs_count = 0;

// 1. Count Workers
$sql_worker = "SELECT * FROM Worker";
$result_worker = mysqli_query($conn, $sql_worker);
$worker_count = mysqli_num_rows($result_worker);

// 2. Count Employers
$sql_employer = "SELECT * FROM Employer";
$result_employer = mysqli_query($conn, $sql_employer);
$employer_count = mysqli_num_rows($result_employer);

// 3. Count Jobs (Hires)
$sql_jobs = "SELECT * FROM Hires";
$result_jobs = mysqli_query($conn, $sql_jobs);
$jobs_count = mysqli_num_rows($result_jobs);
?>

<!-- HERO SECTION -->
<div class="hero">
  <div class="hero-overlay">
    <h1>Welcome To House Hold Network</h1>
    <p>
      A secure platform connecting households with trusted service providers
    </p>
  </div>
</div>

<!-- ABOUT / OFFERING SECTION -->
<div class="about-section">
  <h2>What We Are & What We Offer</h2>
  <p>
    <strong>House Hold Network</strong> is a web-based platform designed to simplify the
    process of hiring reliable household service providers in a secure and verified environment.
    <br><br>
    The platform connects employers with verified workers for a wide range of household services,
    while providing workers with consistent job opportunities and transparent communication.
    Whether you are looking to hire trusted help or searching for household-related work,
    House Hold Network ensures safety, reliability, and efficiency for both parties.
  </p>

  <h3>Services Available on the Platform</h3>
  <ul class="service-list">
    <li>House Helper / Maid</li>
    <li>Cook / Home Chef</li>
    <li>Driver</li>
    <li>Gardener / Mali</li>
    <li>Babysitter / Nanny</li>
    <li>Elderly Caretaker</li>
    <li>Home Nurse (Basic Care)</li>
    <li>Cleaner (Deep Cleaning)</li>
    <li>Electrician</li>
    <li>Plumber</li>
    <li>AC / Appliance Technician</li>
    <li>Laundry & Iron Service</li>
    <li>Security Guard</li>
  </ul>
</div>

<!-- STATISTICS SECTION -->
<div class="stats">
  <div class="stat-box">
    <h2>4.8 ★</h2>
    <p>Platform Rating</p>
  </div>
  <div class="stat-box">
    <h2><?php echo $worker_count; ?>+</h2>
    <p>Registered Workers</p>
  </div>
  <div class="stat-box">
    <h2><?php echo $employer_count; ?>+</h2>
    <p>Active Employers</p>
  </div>
  <div class="stat-box">
    <h2><?php echo $jobs_count; ?>+</h2>
    <p>Jobs Completed</p>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <p><strong>Contact Information</strong></p>
  <p>📞 +880 1234 567890</p>
  <p>📧 support@householdnetwork.com</p>
  <p>📍 Dhaka, Bangladesh</p>
  <p class="copyright">
    © 2026 House Hold Network. All rights reserved.
  </p>
</footer>

</body>
</html>
