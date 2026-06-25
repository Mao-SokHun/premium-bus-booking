<?php
include 'config.php';

$isManagedDb = !empty(getenv('DATABASE_URL')) || getenv('RENDER') === 'true';

function db_connect($host, $port, $dbname, $user, $pass, $sslmode = '') {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    if ($sslmode !== '') {
        $dsn .= ";sslmode=$sslmode";
    }
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $conn;
}

try {
    $conn = db_connect($host, $port, $dbname, $user, $pass, $db_sslmode ?? '');
} catch (PDOException $e) {
    if ($isManagedDb) {
        die('Cannot connect database. Check DATABASE_URL on Render.<br>' . htmlspecialchars($e->getMessage()));
    }

    // local: database not created yet, create it first
    try {
        $conn = db_connect($host, $port, 'postgres', $user, $pass, $db_sslmode ?? '');
        $conn->exec("CREATE DATABASE \"$dbname\"");
        $conn = db_connect($host, $port, $dbname, $user, $pass, $db_sslmode ?? '');
    } catch (PDOException $e2) {
        die('Cannot connect database. Check config.php<br>' . htmlspecialchars($e2->getMessage()));
    }
}

$conn->exec("CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user',
    oauth_provider VARCHAR(20),
    oauth_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->exec("CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    category VARCHAR(20) NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    pickup_date DATE NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    dropoff_date DATE NOT NULL,
    car_type VARCHAR(100) NOT NULL,
    passengers INTEGER NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// migrate existing databases
$conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user'");
$conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS oauth_provider VARCHAR(20)");
$conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS oauth_id VARCHAR(255)");
$conn->exec("ALTER TABLE users ALTER COLUMN password DROP NOT NULL");
$conn->exec("CREATE UNIQUE INDEX IF NOT EXISTS users_oauth_unique ON users (oauth_provider, oauth_id) WHERE oauth_provider IS NOT NULL AND oauth_id IS NOT NULL");
$conn->exec("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS user_id INTEGER REFERENCES users(id)");
$conn->exec("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'pending'");

$conn->exec("CREATE TABLE IF NOT EXISTS vehicles (
    id SERIAL PRIMARY KEY,
    category VARCHAR(20) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(500) NOT NULL,
    seats INTEGER,
    feature1_icon VARCHAR(50),
    feature1_text VARCHAR(100),
    feature2_icon VARCHAR(50),
    feature2_text VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->exec("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS vehicle_id INTEGER REFERENCES vehicles(id)");

$vehicleCount = (int) $conn->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
if ($vehicleCount === 0) {
    $seedVehicles = [
        ['luxury', 'Luxury Car', 'Plush seats, spacious interior, premium amenities for group travel.', 'image/luxury.jpg', 45, 'fa-users', '45 seats', 'fa-snowflake', 'A/C', 1],
        ['luxury', 'Luxury Coaster', 'Elegant group travel with premium interior and smooth ride.', 'image/Luxury Coaster.jpg', 30, 'fa-users', '30 seats', 'fa-couch', 'Leather', 2],
        ['luxury', 'Luxury Van', 'Modern van for family trips and corporate travel in comfort.', 'image/luxury_van.jpg', 15, 'fa-users', '15 seats', 'fa-wifi', 'WiFi', 3],
        ['luxury', 'Luxury Van-H350', 'Premium group travel with spacious design and top comfort.', 'image/luxury_bus.jpg', 15, 'fa-users', '15 seats', 'fa-shield-halved', 'Insured', 4],
        ['luxury', 'Luxury Bus', 'Full-size luxury coach for large groups and VIP events.', 'image/luxury_bus.jpg', 45, 'fa-users', '45 seats', 'fa-star', 'VIP', 5],
        ['premium', 'Premium Car - Denza D9 2024', 'Spacious 7-seat premium car with smooth hybrid/electric ride.', 'image/premium car 2.jpg', 7, 'fa-users', '7 seats', 'fa-bolt', 'Electric', 1],
        ['premium', 'Premium Hyundai Solati H350', 'Reliable premium van for business and long-distance trips.', 'image/Premium car 3.jpg', 12, 'fa-users', '12 seats', 'fa-snowflake', 'A/C', 2],
        ['premium', 'Premium Bus', 'Comfortable premium bus for group tours and events.', 'image/premium_bus.webp', 30, 'fa-users', '30 seats', 'fa-wifi', 'WiFi', 3],
        ['premium', 'Premium Sedan', 'Executive sedan for business travel and airport transfers.', 'image/Premium Car.webp', 4, 'fa-users', '4 seats', 'fa-snowflake', 'A/C', 4],
        ['premium', 'Premium Tour Coach', 'Mid-size coach ideal for temple tours and corporate outings.', 'image/Premium.jpg', 25, 'fa-users', '25 seats', 'fa-shield-halved', 'Insured', 5],
        ['air', 'Air Bus Red', 'Spacious seating and modern interior for group travel.', 'image/air_bus 2.jpg', 45, 'fa-users', '45 seats', 'fa-snowflake', 'A/C', 1],
        ['air', 'Air Bus Blue', 'Comfortable and reliable for family and school trips.', 'image/air_bus 4.jpg', 40, 'fa-users', '40 seats', 'fa-shield-halved', 'Safe', 2],
        ['air', 'Air Bus', 'Budget-friendly option with smooth ride and good ventilation.', 'image/air_bus.jpg', 35, 'fa-users', '35 seats', 'fa-tag', 'Best price', 3],
        ['air', 'Air Bus Classic', 'Dependable coach for schools, families, and community events.', 'image/air_bus 3.jpg', 40, 'fa-users', '40 seats', 'fa-bus', 'Reliable', 4],
    ];
    $stmt = $conn->prepare("INSERT INTO vehicles (category, name, description, image_path, seats, feature1_icon, feature1_text, feature2_icon, feature2_text, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($seedVehicles as $v) {
        $stmt->execute($v);
    }
}

// default admin account (username: admin, password: admin123)
$adminCheck = $conn->query("SELECT id FROM users WHERE username = 'admin'")->fetch();
if (!$adminCheck) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@premiumbus.com', ?, 'admin')");
    $stmt->execute([$hash]);
}
