<?php
include 'check_admin.php';
include 'db.php';

$id = $_GET['id'];
$type = $_GET['type'];
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND category = ?");
$stmt->execute([$id, $type]);
$row = $stmt->fetch();
if (!$row) { header('Location: my_bookings.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form_luxury.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="form-page">
    <div class="form-page-inner">
        <div class="form-card">
            <div class="form-card-head">
                <div class="form-card-icon edit"><i class="fa-solid fa-pen-to-square"></i></div>
                <h2>Edit booking #<?php echo (int) $row['id']; ?></h2>
                <p>Update your reservation details</p>
            </div>

            <form action="booking_update.php" method="post" class="form-card-body">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($type); ?>">

                <div class="form-block">
                    <div class="form-block-label">Contact</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">Full name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-block">
                    <div class="form-block-label">Trip details</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">Pick-up location</label>
                            <input type="text" name="pickup_location" class="form-control" value="<?php echo htmlspecialchars($row['pickup_location']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Pick-up date</label>
                            <input type="date" name="pickup_date" class="form-control" value="<?php echo htmlspecialchars($row['pickup_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Drop-off location</label>
                            <input type="text" name="dropoff_location" class="form-control" value="<?php echo htmlspecialchars($row['dropoff_location']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Drop-off date</label>
                            <input type="date" name="dropoff_date" class="form-control" value="<?php echo htmlspecialchars($row['dropoff_date']); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-block">
                    <div class="form-block-label">Vehicle</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">Car type</label>
                            <input type="text" name="car_type" class="form-control" value="<?php echo htmlspecialchars($row['car_type']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Passengers</label>
                            <input type="number" name="passengers" class="form-control" value="<?php echo (int) $row['passengers']; ?>" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="form-submit-row">
                    <button type="submit" class="btn-form-submit">
                        <i class="fa-solid fa-check"></i> Save changes
                    </button>
                    <div class="form-links">
                        <a href="my_bookings.php">Back to bookings</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
