<?php

function get_vehicles($conn, $category) {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE category = ? AND is_active = true ORDER BY sort_order, id");
    $stmt->execute([$category]);
    return $stmt->fetchAll();
}

function get_vehicle_by_id($conn, $id, $category = null) {
    $sql = "SELECT * FROM vehicles WHERE id = ? AND is_active = true";
    $params = [$id];
    if ($category !== null) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

function vehicle_features($vehicle) {
    $features = [];
    if (!empty($vehicle['feature1_text'])) {
        $features[] = ['icon' => $vehicle['feature1_icon'] ?? 'fa-check', 'text' => $vehicle['feature1_text']];
    }
    if (!empty($vehicle['feature2_text'])) {
        $features[] = ['icon' => $vehicle['feature2_icon'] ?? 'fa-check', 'text' => $vehicle['feature2_text']];
    }
    return $features;
}

function render_vehicle_cards_from_db($conn, $category, $bookUrl) {
    $vehicles = get_vehicles($conn, $category);
    foreach ($vehicles as $vehicle) {
        render_vehicle_card(
            $vehicle['image_path'],
            $vehicle['name'],
            $vehicle['description'],
            $bookUrl,
            vehicle_features($vehicle),
            $category
        );
    }
}
