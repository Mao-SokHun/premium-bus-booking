CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user',
    oauth_provider VARCHAR(20),
    oauth_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS users_oauth_unique
    ON users (oauth_provider, oauth_id)
    WHERE oauth_provider IS NOT NULL AND oauth_id IS NOT NULL;

CREATE TABLE IF NOT EXISTS bookings (
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
    vehicle_id INTEGER REFERENCES vehicles(id),
    passengers INTEGER NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vehicles (
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
);
