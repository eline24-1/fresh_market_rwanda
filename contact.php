<?php
$baseUrl = '';
$pageTitle = 'Contact Us';
require_once __DIR__ . '/includes/functions.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a production system, this would send an email or store in DB.
    $success = true;
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Contact Us</h1>
        <p class="section-subtitle">We'd love to hear from you. Reach out with any questions or feedback.</p>

        <div class="checkout-grid">
            <div class="form-card" style="max-width:none;">
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i> Thank you! Your message has been received. We'll get back to you soon.
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Your Name *</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject">
                    </div>
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                </form>
            </div>

            <div class="order-summary-box">
                <h3>Get in Touch</h3>
                <p style="margin-bottom:12px;"><i class="fa-solid fa-location-dot" style="color:var(--green);width:20px;"></i> KG 11 Ave, Kigali, Rwanda</p>
                <p style="margin-bottom:12px;"><i class="fa-solid fa-phone" style="color:var(--green);width:20px;"></i> +250 788 001 002</p>
                <p style="margin-bottom:12px;"><i class="fa-solid fa-envelope" style="color:var(--green);width:20px;"></i> info@freshmarketrwanda.rw</p>
                <p style="margin-bottom:12px;"><i class="fa-solid fa-clock" style="color:var(--green);width:20px;"></i> Mon - Sat: 7:00 AM - 8:00 PM</p>
                <p><i class="fa-solid fa-truck" style="color:var(--green);width:20px;"></i> Delivery available across Kigali and nearby districts</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
