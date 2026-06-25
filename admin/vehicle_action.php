<?php
session_start();
include '../check_admin.php';
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vehicles.php');
    exit;
}

$action = $_POST['action'] ?? '';

function save_vehicle_image($file, $fallbackPath) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            return [false, 'Invalid image file type'];
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'vehicle_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $dest = dirname(__DIR__) . '/image/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return [false, 'Could not save uploaded image'];
        }
        return [true, 'image/' . $filename];
    }
    $path = trim($fallbackPath ?? '');
    if ($path === '') {
        return [false, 'Please upload an image or enter an image path'];
    }
    return [true, $path];
}

function vehicle_fields_from_post() {
    return [
        'category' => $_POST['category'] ?? '',
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'seats' => (int) ($_POST['seats'] ?? 0),
        'feature1_icon' => trim($_POST['feature1_icon'] ?? 'fa-check'),
        'feature1_text' => trim($_POST['feature1_text'] ?? ''),
        'feature2_icon' => trim($_POST['feature2_icon'] ?? 'fa-check'),
        'feature2_text' => trim($_POST['feature2_text'] ?? ''),
        'sort_order' => (int) ($_POST['sort_order'] ?? 99),
    ];
}

if ($action === 'add') {
    $fields = vehicle_fields_from_post();
    if (!in_array($fields['category'], ['luxury', 'premium', 'air'], true) || $fields['name'] === '' || $fields['description'] === '' || $fields['seats'] <= 0) {
        $_SESSION['msg'] = 'Please fill all required fields';
        header('Location: vehicles.php');
        exit;
    }

    [$ok, $imagePath] = save_vehicle_image($_FILES['image_file'] ?? null, $_POST['image_path'] ?? '');
    if (!$ok) {
        $_SESSION['msg'] = $imagePath;
        header('Location: vehicles.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO vehicles (category, name, description, image_path, seats, feature1_icon, feature1_text, feature2_icon, feature2_text, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $fields['category'], $fields['name'], $fields['description'], $imagePath,
        $fields['seats'], $fields['feature1_icon'], $fields['feature1_text'],
        $fields['feature2_icon'], $fields['feature2_text'], $fields['sort_order'],
    ]);

    $_SESSION['alert'] = 'Vehicle added successfully — users can book it now';
    header('Location: vehicles.php');
    exit;
}

if ($action === 'update') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $fields = vehicle_fields_from_post();

    if ($vehicleId <= 0 || !in_array($fields['category'], ['luxury', 'premium', 'air'], true) || $fields['name'] === '' || $fields['description'] === '' || $fields['seats'] <= 0) {
        $_SESSION['msg'] = 'Please fill all required fields';
        header('Location: vehicle_edit.php?id=' . $vehicleId);
        exit;
    }

    $stmt = $conn->prepare("SELECT image_path FROM vehicles WHERE id = ?");
    $stmt->execute([$vehicleId]);
    $existing = $stmt->fetch();
    if (!$existing) {
        $_SESSION['alert'] = 'Vehicle not found';
        header('Location: vehicles.php');
        exit;
    }

    $imagePath = $existing['image_path'];
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        [$ok, $result] = save_vehicle_image($_FILES['image_file'], '');
        if (!$ok) {
            $_SESSION['msg'] = $result;
            header('Location: vehicle_edit.php?id=' . $vehicleId);
            exit;
        }
        $imagePath = $result;
    } elseif (trim($_POST['image_path'] ?? '') !== '') {
        $imagePath = trim($_POST['image_path']);
    }

    $stmt = $conn->prepare("UPDATE vehicles SET category = ?, name = ?, description = ?, image_path = ?, seats = ?, feature1_icon = ?, feature1_text = ?, feature2_icon = ?, feature2_text = ?, sort_order = ? WHERE id = ?");
    $stmt->execute([
        $fields['category'], $fields['name'], $fields['description'], $imagePath,
        $fields['seats'], $fields['feature1_icon'], $fields['feature1_text'],
        $fields['feature2_icon'], $fields['feature2_text'], $fields['sort_order'], $vehicleId,
    ]);

    $_SESSION['alert'] = 'Vehicle updated successfully';
    header('Location: vehicles.php');
    exit;
}

if ($action === 'toggle') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $conn->prepare("UPDATE vehicles SET is_active = NOT is_active WHERE id = ?")->execute([$vehicleId]);
    $_SESSION['alert'] = 'Vehicle visibility updated';
    header('Location: vehicles.php');
    exit;
}

if ($action === 'delete') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $conn->prepare("UPDATE bookings SET vehicle_id = NULL WHERE vehicle_id = ?")->execute([$vehicleId]);
    $conn->prepare("DELETE FROM vehicles WHERE id = ?")->execute([$vehicleId]);
    $_SESSION['alert'] = 'Vehicle deleted';
    header('Location: vehicles.php');
    exit;
}

header('Location: vehicles.php');
exit;
