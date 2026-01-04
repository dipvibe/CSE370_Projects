<?php
$host = "localhost";
$user = "root";
$pass = "root"; // <--- Update this to match your MySQL password

// Create connection without database first
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the SQL file
$sqlFile = 'house_hold_network.sql';
if (!file_exists($sqlFile)) {
    die("Error: SQL file not found.");
}

$sql = file_get_contents($sqlFile);

// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check if there are more result sets
    } while ($conn->next_result());
    
    echo "Database and tables created successfully!<br>";
    echo "You can now delete this file and start using the application.";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
