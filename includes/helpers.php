<?php

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function status_badge($status) {
    $map = [
        'pending'   => 'badge-pending',
        'confirmed' => 'badge-confirmed',
        'completed' => 'badge-completed',
        'cancelled' => 'badge-cancelled',
    ];
    $class = $map[$status] ?? 'badge-pending';
    $label = ucfirst($status);
    return '<span class="status-badge ' . $class . '">' . htmlspecialchars($label) . '</span>';
}

function category_label($cat) {
    $map = ['luxury' => 'Luxury', 'premium' => 'Premium', 'air' => 'Air Bus'];
    return $map[$cat] ?? ucfirst($cat);
}

function category_icon($cat) {
    $map = ['luxury' => 'fa-crown', 'premium' => 'fa-star', 'air' => 'fa-bus'];
    return $map[$cat] ?? 'fa-car';
}

function category_tag($cat) {
    $label = category_label($cat);
    $icon = category_icon($cat);
    return '<span class="cat-pill cat-' . htmlspecialchars($cat) . '"><i class="fa-solid ' . $icon . '"></i> ' . htmlspecialchars($label) . '</span>';
}

function render_vehicle_card($image, $title, $description, $bookUrl, $features = [], $tag = 'premium') {
    $tagClass = in_array($tag, ['luxury', 'premium', 'air']) ? $tag : 'premium';
    $featuresHtml = '';
    foreach ($features as $feature) {
        $featuresHtml .= '<span class="vehicle-spec"><i class="fa-solid ' . htmlspecialchars($feature['icon']) . '"></i> '
            . htmlspecialchars($feature['text']) . '</span>';
    }
    ?>
    <div class="col-md-6 col-lg-4">
        <article class="vehicle-card">
            <div class="vehicle-card-media">
                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                <span class="vehicle-tag tag-<?php echo $tagClass; ?>"><?php echo ucfirst($tagClass); ?></span>
            </div>
            <div class="vehicle-card-body">
                <h3 class="vehicle-card-title"><?php echo htmlspecialchars($title); ?></h3>
                <p class="vehicle-card-desc"><?php echo htmlspecialchars($description); ?></p>
                <?php if ($featuresHtml) { ?><div class="vehicle-specs"><?php echo $featuresHtml; ?></div><?php } ?>
                <div class="vehicle-card-actions">
                    <a class="btn btn-gold" href="<?php echo htmlspecialchars($bookUrl); ?>" style="width:100%;">
                        <i class="fa-solid fa-calendar-check"></i> Reserve
                    </a>
                </div>
            </div>
        </article>
    </div>
    <?php
}

function booking_table_actions($row, $category, $redirect = '') {
    $redirectField = $redirect !== ''
        ? '<input type="hidden" name="redirect" value="' . htmlspecialchars($redirect) . '">'
        : '';
    ?>
    <div class="tbl-actions">
        <a class="tbl-btn tbl-edit" href="edit_booking.php?id=<?php echo (int) $row['id']; ?>&type=<?php echo htmlspecialchars($category); ?>" title="Edit">
            <i class="fa-solid fa-pen"></i>
        </a>
        <form action="booking_delete.php" method="post" onsubmit="return confirm('Delete this booking?');">
            <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php echo $redirectField; ?>
            <button type="submit" class="tbl-btn tbl-delete" title="Delete">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    </div>
    <?php
}
