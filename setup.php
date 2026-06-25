<?php
include 'config.php';

$note = [];

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=postgres", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $check = $conn->query("SELECT 1 FROM pg_database WHERE datname = '$dbname'")->fetch();
    if (!$check) {
        $conn->exec("CREATE DATABASE $dbname");
        $note[] = "Created database $dbname";
    } else {
        $note[] = "Database $dbname already exists";
    }

    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS bookings (
        id SERIAL PRIMARY KEY,
        category VARCHAR(20) NOT NULL,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        pickup_location VARCHAR(255) NOT NULL,
        pickup_date DATE NOT NULL,
        dropoff_location VARCHAR(255) NOT NULL,
        dropoff_date DATE NOT NULL,
        car_type VARCHAR(100) NOT NULL,
        passengers INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $note[] = 'Tables created. Open index.php to register.';
} catch (PDOException $e) {
    $note[] = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="login">
    <h2>Setup Database</h2>
    <?php foreach ($note as $line) { echo '<p>' . htmlspecialchars($line) . '</p>'; } ?>
    <p><a href="index.php">Go to Register</a></p>
</div>
</body>
</html>
