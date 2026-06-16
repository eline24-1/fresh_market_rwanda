<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4><i class="fa-solid fa-leaf"></i> Fresh Market Rwanda</h4>
                <p>Your trusted online market for fresh fruits, vegetables, dairy, grains and more — delivered straight from local farms to your doorstep.</p>
            </div>
            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?= $baseUrl ?? '' ?>index.php">Home</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>products.php">Shop</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>cart.php">Cart</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>about.php">About Us</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>contact.php">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4>Categories</h4>
                <ul>
                    <li><a href="<?= $baseUrl ?? '' ?>products.php?category=fruits">Fruits</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>products.php?category=vegetables">Vegetables</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>products.php?category=dairy-eggs">Dairy &amp; Eggs</a></li>
                    <li><a href="<?= $baseUrl ?? '' ?>products.php?category=grains-cereals">Grains &amp; Cereals</a></li>
                </ul>
            </div>
            <div>
                <h4>Contact Us</h4>
                <ul>
                    <li><i class="fa-solid fa-location-dot"></i> KG 11 Ave, Kigali, Rwanda</li>
                    <li><i class="fa-solid fa-phone"></i> +250 788 001 002</li>
                    <li><i class="fa-solid fa-envelope"></i> info@freshmarketrwanda.rw</li>
                    <li><i class="fa-solid fa-clock"></i> Mon - Sat: 7:00 AM - 8:00 PM</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> Fresh Market Rwanda. All rights reserved.
        </div>
    </div>
</footer>

<script src="<?= $baseUrl ?? '' ?>assets/js/main.js"></script>
</body>
</html>
