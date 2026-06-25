<?php include 'check_login.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="image/logo 2.jpg">
</head>
<body>
<?php include 'header.php'; ?>

<section class="hero-cinema">
    <div class="hero-cinema-bg"></div>
    <div class="hero-cinema-inner">
        <div class="hero-cinema-copy">
            <div class="hero-overline"><span>Premium Transport · Cambodia</span></div>
            <h1>Every journey<br>deserves <em>comfort.</em></h1>
            <p class="hero-cinema-lead">Luxury vans, premium coaches, and air buses — curated for weddings, corporate events, and family adventures across the kingdom.</p>
            <div class="hero-cta-row">
                <a href="form_luxury.php" class="btn btn-gold">Reserve a vehicle</a>
                <a href="#fleet" class="btn btn-ghost">Explore fleet</a>
            </div>
        </div>
        <div class="hero-side-panel">
            <div class="hero-stat-item"><strong>3</strong><span>Vehicle classes</span></div>
            <div class="hero-stat-item"><strong>24/7</strong><span>Concierge support</span></div>
            <div class="hero-stat-item"><strong>100%</strong><span>Insured fleet</span></div>
        </div>
    </div>
</section>

<div class="page-wrap" id="fleet">
    <div class="section-intro">
        <div class="section-num">01</div>
        <div class="section-intro-text">
            <div class="eyebrow">The Collection</div>
            <h2 class="page-title">Choose your carriage</h2>
            <p class="section-desc">Three distinct classes — each maintained to the highest standard, each ready for your next chapter on the road.</p>
        </div>
    </div>

    <div class="fleet-editorial">
        <article class="fleet-row">
            <div class="fleet-row-media">
                <img src="image/luxury_van.jpg" alt="Luxury">
                <span class="fleet-row-num">I</span>
                <span class="fleet-row-tag">Luxury Class</span>
            </div>
            <div class="fleet-row-body">
                <h3 class="fleet-row-title">Luxury Car</h3>
                <p class="fleet-row-desc">Plush interiors, whisper-quiet cabins, and white-glove service for VIP events, weddings, and executive delegations.</p>
                <div class="fleet-row-specs">
                    <span><i class="fa-solid fa-users"></i> 15 seats</span>
                    <span><i class="fa-solid fa-snowflake"></i> Climate control</span>
                </div>
                <div class="fleet-row-actions">
                    <a class="btn btn-gold" href="form_luxury.php">Reserve</a>
                    <a class="btn btn-ghost" href="view_luxury_bus.php">View fleet</a>
                </div>
            </div>
        </article>

        <article class="fleet-row">
            <div class="fleet-row-media">
                <img src="image/premium_bus.webp" alt="Premium">
                <span class="fleet-row-num">II</span>
                <span class="fleet-row-tag">Premium Class</span>
            </div>
            <div class="fleet-row-body">
                <h3 class="fleet-row-title">Premium Car</h3>
                <p class="fleet-row-desc">The perfect equilibrium of comfort and value — ideal for corporate retreats, temple tours, and long-distance group travel.</p>
                <div class="fleet-row-specs">
                    <span><i class="fa-solid fa-users"></i> 30 seats</span>
                    <span><i class="fa-solid fa-wifi"></i> Onboard WiFi</span>
                </div>
                <div class="fleet-row-actions">
                    <a class="btn btn-gold" href="form_premium.php">Reserve</a>
                    <a class="btn btn-ghost" href="view_premium_bus.php">View fleet</a>
                </div>
            </div>
        </article>

        <article class="fleet-row">
            <div class="fleet-row-media">
                <img src="image/air_bus.jpg" alt="Air Bus">
                <span class="fleet-row-num">III</span>
                <span class="fleet-row-tag">Air Bus Class</span>
            </div>
            <div class="fleet-row-body">
                <h3 class="fleet-row-title">Air Bus</h3>
                <p class="fleet-row-desc">Spacious, dependable, and thoughtfully priced — the trusted choice for schools, families, and community outings.</p>
                <div class="fleet-row-specs">
                    <span><i class="fa-solid fa-users"></i> 45 seats</span>
                    <span><i class="fa-solid fa-shield"></i> Fully insured</span>
                </div>
                <div class="fleet-row-actions">
                    <a class="btn btn-gold" href="form_air.php">Reserve</a>
                    <a class="btn btn-ghost" href="view_air_bus.php">View fleet</a>
                </div>
            </div>
        </article>
    </div>

    <section class="promise-section">
        <div class="section-intro">
            <div class="section-num">02</div>
            <div class="section-intro-text">
                <div class="eyebrow">Our Promise</div>
                <h2 class="page-title">Why travelers choose us</h2>
            </div>
        </div>

        <div class="why-strip">
            <div class="why-item">
                <i class="fa-solid fa-shield-halved"></i>
                <h4>Fully insured</h4>
                <p>Licensed drivers and comprehensive coverage on every vehicle in our fleet.</p>
            </div>
            <div class="why-item">
                <i class="fa-solid fa-clock"></i>
                <h4>Punctual always</h4>
                <p>We arrive before you do. Your schedule is sacred to us.</p>
            </div>
            <div class="why-item">
                <i class="fa-solid fa-headset"></i>
                <h4>Concierge support</h4>
                <p>Real humans available around the clock for any request.</p>
            </div>
            <div class="why-item">
                <i class="fa-solid fa-gem"></i>
                <h4>Honest pricing</h4>
                <p>Transparent quotes with no hidden fees — ever.</p>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
