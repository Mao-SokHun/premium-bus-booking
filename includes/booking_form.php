<?php
if (!function_exists('is_admin')) {
    include_once __DIR__ . '/helpers.php';
}

function render_booking_form($config) {
    $category = $config['category'];
    $icon = $config['icon'];
    $title = $config['title'];
    $subtitle = $config['subtitle'];
    $vehicles = $config['vehicles'] ?? [];
    $listUrl = $config['list_url'] ?? 'my_bookings.php';
    $theme = $config['theme'] ?? 'premium';
    $showListLink = $config['show_list_link'] ?? is_admin();
    ?>
    <section class="form-page">
        <div class="form-page-inner">
            <div class="form-card">
                <div class="form-card-head">
                    <div class="form-card-icon <?php echo htmlspecialchars($theme); ?>">
                        <i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i>
                    </div>
                    <h2><?php echo htmlspecialchars($title); ?></h2>
                    <p><?php echo htmlspecialchars($subtitle); ?></p>
                </div>

                <form action="booking_submit.php" method="post" class="form-card-body">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">

                    <div class="form-block">
                        <div class="form-block-label">Contact</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">Full name</label>
                                <input type="text" name="name" class="form-control" placeholder="Your name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="097 49 44 390" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-block">
                        <div class="form-block-label">Trip details</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">Pick-up location</label>
                                <input type="text" name="pickup_location" class="form-control" placeholder="Address or city" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Pick-up date</label>
                                <input type="date" name="pickup_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Drop-off location</label>
                                <input type="text" name="dropoff_location" class="form-control" placeholder="Address or city" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Drop-off date</label>
                                <input type="date" name="dropoff_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-block">
                        <div class="form-block-label">Vehicle</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">Select vehicle</label>
                                <select name="vehicle_id" id="vehicleSelect" class="form-select" required>
                                    <option value="">Choose a vehicle</option>
                                    <?php foreach ($vehicles as $vehicle) { ?>
                                    <option value="<?php echo (int) $vehicle['id']; ?>" data-image="<?php echo htmlspecialchars($vehicle['image_path']); ?>">
                                        <?php echo htmlspecialchars($vehicle['name']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Passengers</label>
                                <input type="number" name="passengers" class="form-control" min="1" placeholder="Number of people" required>
                            </div>
                            <div class="col-12">
                                <div class="booking-vehicle-preview" id="vehiclePreview" hidden>
                                    <img id="vehiclePreviewImg" src="" alt="Selected vehicle">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-submit-row">
                        <button type="submit" class="btn-form-submit">
                            <i class="fa-solid fa-check"></i> Confirm booking
                        </button>
                        <div class="form-links">
                            <?php if ($showListLink) { ?><a href="<?php echo htmlspecialchars($listUrl); ?>">View bookings</a><?php } ?>
                            <a href="homepage.php">Back home</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
    (function() {
        var select = document.getElementById('vehicleSelect');
        var preview = document.getElementById('vehiclePreview');
        var img = document.getElementById('vehiclePreviewImg');
        if (!select || !preview || !img) return;
        function updatePreview() {
            var option = select.options[select.selectedIndex];
            var src = option ? option.getAttribute('data-image') : '';
            if (src) {
                img.src = src;
                preview.hidden = false;
            } else {
                preview.hidden = true;
            }
        }
        select.addEventListener('change', updatePreview);
        updatePreview();
    })();
    </script>
    <?php
}
