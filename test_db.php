<?php
// Database connection test for InfinityFree hosting
// Run this locally to test remote database connection

echo "Testing remote database connection...\n\n";

$host = 'localhost';
$user = 'ektamultp_bit';
$pass = 'zDJ+iZNrsU,-YKp4';
$db = 'ektamultp_bit';

echo "Host: $host\n";
echo "User: $user\n";
echo "Database: $db\n\n";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "❌ Connection Failed: " . $conn->connect_error . "\n";

    // Common troubleshooting tips
    echo "\nPossible solutions:\n";
    echo "1. Verify credentials are correct\n";
    echo "2. Check if remote MySQL connections are allowed\n";
    echo "3. Ensure database exists and is accessible\n";
    echo "4. Check for firewall restrictions\n";
    echo "5. Try connecting from a different network\n";
} else {
    echo "✅ Database Connection Successful!\n";

    // Test basic queries
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "Tables found: " . $result->num_rows . "\n";
        echo "Available tables:\n";
        while ($row = $result->fetch_array()) {
            echo "  - " . $row[0] . "\n";
        }
    } else {
        echo "❌ Query failed: " . $conn->error . "\n";
    }

    $conn->close();
}

echo "\nTest completed.\n";
?>