<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'policy';

$title = "Shipping & Delivery Policy | Kyra Pet Shop New Delhi";
$desc = "Learn how Kyra Pet Shop safely delivers and transports healthy puppies to your doorstep across India.";
$meta_key = "pet shipping, puppy delivery, safe pet transport, kyra pet shop delivery";

require __DIR__ . '/includes/header.php';
if (function_exists('banner')) banner('Shipping & Delivery Policy', 'Shipping Policy', 'Ensuring a safe and comfortable journey for your new best friend.');
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
                <p>Delivering a live puppy is entirely different from shipping standard goods. At <strong>Kyra Pet
                        Shop</strong>, the safety, comfort, and health of the puppy during transit is our absolute
                    priority. We use specialized, humane pet transport methods to bring your new family member home.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">1.
                    Handover & Shop Visit (Recommended)</h3>
                <p>We always encourage families to visit our shop in Patel Nagar, New Delhi. This allows you to interact
                    with the puppy, review the vaccination records with our team, and take your pet home personally in a
                    comfortable, stress-free environment.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">2.
                    Local Delivery (Delhi NCR)</h3>
                <p>For pet parents residing in Delhi NCR, we offer direct doorstep delivery via our trained staff or
                    specialized local pet cabs. Delivery is usually scheduled within <strong>24 to 48 hours</strong>
                    after booking confirmation and health checks.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">3.
                    Domestic Delivery (Rest of India)</h3>
                <p>For clients outside Delhi NCR, we arrange delivery through trusted and certified pet relocation
                    partners via Air or safe Rail transport.</p>
                <ul style="padding-left: 20px; margin-bottom: 20px;">
                    <li style="margin-bottom:8px;"><strong>Transit Time:</strong> Typically takes 3 to 7 business days
                        for documentation, vet clearance, and flight/train booking.</li>
                    <li style="margin-bottom:8px;"><strong>Crating:</strong> Puppies are transported in spacious,
                        IATA-approved sanitized travel crates.</li>
                    <li style="margin-bottom:8px;"><strong>Documentation:</strong> We provide Fit-to-Fly veterinary
                        certificates and up-to-date vaccination records.</li>
                </ul>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">4.
                    Delivery Charges & Delays</h3>
                <p>Delivery charges are calculated based on the destination distance and the mode of transport
                    (Air/Rail/Cab). Due to unpredictable factors like weather conditions, airline embargoes on live
                    animals, or vet advice, shipments may occasionally be delayed to ensure the puppy's safety.</p>
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
                    <a href="privacy-policy.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Privacy
                        Policy →</a>
                    <a href="refund-policy.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Refund
                        Policy →</a>
                    <a href="terms-condition.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Terms
                        & Conditions →</a>
                </div>
            </div>

            <div
                style="background:linear-gradient(145deg, var(--navy, #0f172a) 0%, #1e293b 100%); border-radius:20px; padding:28px 24px; color:#fff; text-align:center;">
                <h4 style="color:#fff; font-size:18px; font-weight:800; margin-bottom:10px;">Safe Pet Delivery</h4>
                <p style="color:#cbd5e1; font-size:14px; margin-bottom:20px;">Want to know the shipping charges to your
                    city? Contact us on WhatsApp.</p>
                <a href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I want to know the puppy delivery process and charges.') ?>"
                    target="_blank" class="btn btn-teal"
                    style="width:100%; justify-content:center; margin-bottom:10px;">💬 WhatsApp Us</a>
            </div>
        </aside>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>