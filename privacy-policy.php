<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'policy';

$title = "Privacy Policy | Kyra Pet Shop New Delhi";
$desc = "Read the Privacy Policy of Kyra Pet Shop regarding personal data protection for pet parents and online inquiries.";
$meta_key = "privacy policy, kyra pet shop, pet parent privacy, data protection";

require __DIR__ . '/includes/header.php';
if (function_exists('banner')) banner('Privacy Policy', 'Privacy Policy', 'We value your trust and are committed to protecting your personal information.');
?>

<style>
.policy-sec {
    padding: 60px 0;
    background: #f8fafc;
}

.policy-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 40px;
    align-items: start;
}

.policy-card {
    background: #fff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    border: 1px solid #e2e8f0;
}

.policy-sidebar {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

@media (max-width: 992px) {
    .policy-grid {
        grid-template-columns: 1fr;
    }

    .policy-sidebar {
        position: static;
    }
}

@media (max-width: 576px) {
    .policy-sec {
        padding: 40px 0;
    }

    .policy-card {
        padding: 24px;
    }
}
</style>

<section class="policy-sec">
    <div class="wrap policy-grid">

        <!-- Left Content -->
        <article class="policy-card">
            <p style="color:#64748b; margin-bottom: 30px; font-size:14.5px;"><strong>Last Updated:</strong>
                <?= date('F d, Y'); ?></p>

            <div class="kyra-article-body" style="color: #334155; font-size: 16.5px; line-height: 1.8;">
                <p>Welcome to <strong>Kyra Pet Shop</strong>. We respect your privacy and are deeply committed to
                    protecting your personal data. This policy outlines how we collect, use, and safeguard your
                    information when you visit our website, inquire about our puppies, or use our services.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">1.
                    Information We Collect</h3>
                <p>To help you find the perfect furry companion and arrange safe deliveries, we collect:</p>
                <ul style="padding-left: 20px; margin-bottom: 20px;">
                    <li style="margin-bottom:8px;"><strong>Personal Details:</strong> Name, phone number, and email
                        address submitted via our inquiry forms or WhatsApp.</li>
                    <li style="margin-bottom:8px;"><strong>Shipping Information:</strong> Residential address required
                        for doorstep delivery of your puppy and pet accessories.</li>
                    <li style="margin-bottom:8px;"><strong>Pet Preferences:</strong> Information regarding the breed,
                        gender, and age preferences you share with our team.</li>
                </ul>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">2. How
                    We Use Your Information</h3>
                <p>We use your information exclusively to:</p>
                <ul style="padding-left: 20px; margin-bottom: 20px;">
                    <li style="margin-bottom:8px;">Share live photos, videos, and health records of available puppies
                        directly with you.</li>
                    <li style="margin-bottom:8px;">Coordinate safe and timely transport/delivery of the puppy to your
                        location.</li>
                    <li style="margin-bottom:8px;">Provide post-purchase guidance, vaccination reminders, and dietary
                        consultation.</li>
                </ul>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">3. Data
                    Sharing & Security</h3>
                <p>We <strong>do not sell, trade, or rent</strong> your personal contact information to third-party
                    marketing agencies. Your address and contact details are only shared with trusted pet-relocation
                    partners (drivers/airlines) strictly for the purpose of safely delivering your puppy.</p>
                <p>All WhatsApp conversations, photos, and health records shared with you remain confidential between
                    you and the Kyra Pet Shop team.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">4.
                    Happy Customers Gallery</h3>
                <p>We love featuring our happy pet parents! However, we only upload your photos or videos to our
                    "Customer Stories" or social media platforms with your explicit verbal or written consent.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">5.
                    Contact Us</h3>
                <p>If you have any questions regarding your data privacy, please contact:</p>
                <div
                    style="background: #f1f5f9; padding: 20px; border-radius: 12px; border-left: 4px solid var(--teal, #0d9488); margin-top: 15px;">
                    <strong>Kyra Pet Shop</strong><br>
                    WZ-23 Khampur, Patel Nagar, New Delhi - 110008<br>
                    <strong>Phone:</strong> <?= h($site['phone'] ?? '+91 9582799502') ?><br>
                    <strong>Email:</strong> <?= h($site['email'] ?? 'kyrapetshop12@gmail.com') ?>
                </div>
            </div>
        </article>

        <!-- Right Sidebar -->
        <aside class="policy-sidebar">
            <div
                style="background:#fff; border-radius:20px; padding:24px; border:1px solid #e2e8f0; box-shadow:0 10px 30px rgba(15,23,42,0.04);">
                <h4
                    style="font-size:17px; font-weight:800; color:var(--ink); margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f1f5f9;">
                    📍 Quick Links</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a href="terms-condition.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Terms
                        & Conditions →</a>
                    <a href="shipping-return.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Shipping
                        Policy →</a>
                    <a href="refund-policy.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Refund
                        Policy →</a>
                </div>
            </div>

            <div
                style="background:linear-gradient(145deg, var(--navy, #0f172a) 0%, #1e293b 100%); border-radius:20px; padding:28px 24px; color:#fff; text-align:center;">
                <h4 style="color:#fff; font-size:18px; font-weight:800; margin-bottom:10px;">Find Your Match</h4>
                <p style="color:#cbd5e1; font-size:14px; margin-bottom:20px;">Browse our healthy, 100% vaccinated
                    puppies.</p>
                <a href="breeds.php" class="btn btn-teal" style="width:100%; justify-content:center;">View Puppies</a>
            </div>
        </aside>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>