<?php 
include_once('includes/config.php'); 
include_once('config/connect.php'); // Ensure DB connection is available

// Fetch fresh contact/social details dynamically from database
$footer_contact_q = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$fc_data = $footer_contact_q ? mysqli_fetch_assoc($footer_contact_q) : [];

// Map database columns dynamically (Supports both full names and short names)
$fb_url = !empty($fc_data['facebook']) ? $fc_data['facebook'] : (!empty($fc_data['fb']) ? $fc_data['fb'] : ($site['fb'] ?? ''));
$insta_url = !empty($fc_data['instagram']) ? $fc_data['instagram'] : (!empty($fc_data['insta']) ? $fc_data['insta'] : ($site['insta'] ?? ''));
$yt_url = !empty($fc_data['youtube']) ? $fc_data['youtube'] : (!empty($fc_data['yt']) ? $fc_data['yt'] : ($site['yt'] ?? ''));
$tw_url = !empty($fc_data['twitter']) ? $fc_data['twitter'] : '';
$in_url = !empty($fc_data['linkdin']) ? $fc_data['linkdin'] : '';

// Fetch dynamic Address, Phone, and Email to ensure everything is 100% updated
$address = !empty($fc_data['address']) ? $fc_data['address'] : ($site['addr'] ?? 'WZ-23 Khampur Patel Nagar, New Delhi - 110008');
$phone = !empty($fc_data['phone']) ? $fc_data['phone'] : ($site['phone'] ?? '+91 9582799502');
$clean_phone = preg_replace('/[^0-9+]/', '', $phone);
$email = !empty($fc_data['email']) ? $fc_data['email'] : ($site['email'] ?? 'kyrapetshop12@gmail.com');
$working_hours = !empty($fc_data['working_hours']) ? $fc_data['working_hours'] : ($site['hours'] ?? 'Tuesday - Sunday, 9 AM to 10 PM');

// WhatsApp Number Logic
$wa_raw = !empty($fc_data['whatsapp']) ? $fc_data['whatsapp'] : (!empty($fc_data['wa']) ? $fc_data['wa'] : ($site['wa'] ?? $clean_phone));
$wa_clean = preg_replace('/[^0-9+]/', '', $wa_raw);
?>

<style>
/* ================= PREMIUM FOOTER THEME ================= */
.site-footer {
    background-color: var(--navy, #0f172a);
    color: #cbd5e1;
    padding: 70px 0 20px;
    font-size: 15px;
    border-top: 4px solid var(--teal, #0d9488);
}

.ft-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 40px;
    margin-bottom: 50px;
}

@media (max-width: 992px) {
    .ft-grid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
}

@media (max-width: 576px) {
    .ft-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}

.ft-brand img {
    height: 65px;
    background: #ffffff;
    padding: 8px 14px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.ft-brand p {
    line-height: 1.7;
    margin-bottom: 24px;
    max-width: 320px;
}

.ft-social {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.ft-social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.ft-social a svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

.ft-social a:hover {
    background: var(--teal, #0d9488);
    border-color: var(--teal, #0d9488);
    transform: translateY(-3px);
}

.site-footer h4 {
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 22px;
    position: relative;
    padding-bottom: 12px;
}

.site-footer h4::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 30px;
    height: 2px;
    background: var(--teal, #0d9488);
}

.site-footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.site-footer ul li {
    margin-bottom: 12px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.site-footer ul li a {
    color: #cbd5e1;
    text-decoration: none;
    transition: color 0.2s, padding-left 0.2s;
}

.site-footer ul li a:hover {
    color: var(--teal, #0d9488);
    padding-left: 5px;
}

.ft-contact-list li {
    line-height: 1.6;
}

.ft-contact-list svg {
    width: 18px;
    height: 18px;
    min-width: 18px;
    margin-top: 3px;
    color: var(--teal, #0d9488);
}

.ft-bottom {
    padding-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 13.5px;
}

.ft-bottom a {
    color: var(--teal, #0d9488);
    text-decoration: none;
    font-weight: 600;
}

.ft-bottom a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .ft-bottom {
        justify-content: center;
        text-align: center;
    }
}
</style>

<footer class="site-footer">
    <div class="wrap">
        <div class="ft-grid">

            <!-- Brand & Social -->
            <div class="ft-brand">
                <a href="index"><img src="assets/img/logo.png" alt="Kyra Pet Shop"></a>
                <p>Healthy, premium dog breeds in New Delhi. Find your perfect four-legged companion with us today. Vet
                    checked & 100% vaccinated.</p>

                <div class="ft-social">
                    <?php if (!empty($fb_url) && $fb_url !== '#'): ?>
                    <a href="<?= htmlspecialchars($fb_url) ?>" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg viewBox="0 0 320 512">
                            <path
                                d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($insta_url) && $insta_url !== '#'): ?>
                    <a href="<?= htmlspecialchars($insta_url) ?>" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg viewBox="0 0 448 512">
                            <path
                                d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.5-25.9-7.1-48.9-24.5-66.3-17.4-17.4-40.4-23-66.3-24.5-27.3-1.6-109.1-1.6-136.4 0-25.9 1.5-48.9 7.1-66.3 24.5-17.4 17.4-23 40.4-24.5 66.3-1.6 27.3-1.6 109.1 0 136.4 1.5 25.9 7.1 48.9 24.5 66.3 17.4 17.4 40.4 23 66.3 24.5 27.3 1.6 109.1 1.6 136.4 0 25.9-1.5 48.9-7.1 66.3-24.5 17.4-17.4 23-40.4 24.5-66.3 1.6-27.3 1.6-109.1 0-136.4zm-48.6 170c-7.5 18.2-21.5 32.2-39.7 39.7-22.1 9.1-74.5 7.7-106.6 7.7-32 0-84.6 1.4-106.6-7.7-18.2-7.5-32.2-21.5-39.7-39.7-9.1-22.1-7.7-74.5-7.7-106.6 0-32-1.4-84.6 7.7-106.6 7.5-18.2 21.5-32.2 39.7-39.7 22.1-9.1 74.5-7.7 106.6-7.7 32 0 84.6-1.4 106.6 7.7 18.2 7.5 32.2 21.5 39.7 39.7 9.1 22.1 7.7 74.5 7.7 106.6 0 32 1.4 84.6-7.7 106.6z" />
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($yt_url) && $yt_url !== '#'): ?>
                    <a href="<?= htmlspecialchars($yt_url) ?>" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg viewBox="0 0 576 512">
                            <path
                                d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z" />
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($tw_url) && $tw_url !== '#'): ?>
                    <a href="<?= htmlspecialchars($tw_url) ?>" aria-label="Twitter / X" target="_blank" rel="noopener">
                        <svg viewBox="0 0 512 512">
                            <path
                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($in_url) && $in_url !== '#'): ?>
                    <a href="<?= htmlspecialchars($in_url) ?>" aria-label="LinkedIn" target="_blank" rel="noopener">
                        <svg viewBox="0 0 448 512">
                            <path
                                d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z" />
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4>Explore</h4>
                <ul>
                    <li><a href="about">About Us</a></li>
                    <li><a href="breeds">Available Puppies</a></li>
                    <li><a href="how-it-works">How It Works</a></li>
                    <li><a href="location">Locations & Guides</a></li>
                    <li><a href="reviews">Customer Stories</a></li>
                    <li><a href="gallery">Gallery</a></li>
                    <li><a href="blog">Blog</a></li>
                </ul>
            </div>

            <!-- Legal & Support -->
            <div>
                <h4>Support & Policies</h4>
                <ul>
                    <li><a href="contact">Contact Us</a></li>
                    <li><a href="terms-condition">Terms & Conditions</a></li>
                    <li><a href="privacy-policy">Privacy Policy</a></li>
                    <li><a href="refund-policy">Refund Policy</a></li>
                    <li><a href="shipping-return">Shipping & Delivery</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4>Get in Touch</h4>
                <ul class="ft-contact-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span><?= h($address) ?></span>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        <a href="tel:<?= h($clean_phone) ?>"><?= h($phone) ?></a>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <a href="mailto:<?= h($email) ?>"><?= h($email) ?></a>
                    </li>
                    <!-- <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span><?= h($working_hours) ?></span>
                    </li> -->
                </ul>
            </div>

        </div>

        <div class="ft-bottom">
            <span>&copy; <?= date('Y') ?> Kyra Pet Shop. All rights reserved.</span>
            <span>Design & Developed by <a href="https://digitalwebtrackers.com" target="_blank" rel="noopener">Digital
                    Web Trackers</a>.</span>
        </div>
    </div>
</footer>

<a class="fab call" href="tel:<?= h($clean_phone) ?>" aria-label="Call Kyra Pet Shop">
    <svg viewBox="0 0 24 24" fill="currentColor">
        <path
            d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z" />
    </svg>
</a>
<a class="fab" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
    href="https://wa.me/<?= h($wa_clean) ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I would like to know more about your puppies.') ?>">
    <svg viewBox="0 0 32 32" fill="currentColor">
        <path
            d="M16 3C9 3 3.4 8.6 3.4 15.5c0 2.3.6 4.4 1.7 6.3L3 29l7.4-2c1.8 1 3.8 1.5 5.6 1.5 7 0 12.6-5.6 12.6-12.5S23 3 16 3zm6.5 17.7c-.3.8-1.7 1.5-2.3 1.6-.6.1-1.3.1-2.1-.1-.5-.2-1.1-.4-1.9-.7-3.3-1.4-5.4-4.7-5.6-4.9-.2-.2-1.3-1.7-1.3-3.2s.8-2.300 1.100-2.600c.3-.3.6-.4.8-.4h.6c.2 0 .5-.1.7.5l1 2.300c.1.2.1.4 0 .6l-.4.6-.5.6c-.2.2-.4.4-.2.8.2.4 1 1.600 2.100 2.600 1.400 1.200 2.600 1.600 3 1.800.4.2.6.1.8-.1.2-.3 1-1.100 1.200-1.500.3-.4.5-.3.8-.2l2.200 1c.4.2.6.3.7.4.1.2.1.8-.2 1.500z" />
    </svg>
</a>

<script src="assets/js/main.js" defer></script>
</body>

</html>