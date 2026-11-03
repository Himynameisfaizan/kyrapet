<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'policy';

$title = "Refund and Cancellation Policy | Kyra Pet Shop New Delhi";
$desc = "Read the refund and cancellation policy of Kyra Pet Shop for booking and bringing home healthy, vaccinated puppies.";
$meta_key = "refund policy, cancellation policy, kyra pet shop, puppy booking, new delhi";

require __DIR__ . '/includes/header.php';
if (function_exists('banner')) banner('Refund & Cancellation Policy', 'Refund Policy', 'Clear and transparent policies for your peace of mind.');
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
                <p>At <strong>Kyra Pet Shop</strong>, our primary focus is the health and well-being of our puppies.
                    Dealing with live pets requires strict protocols to ensure their safety. Please read our
                    cancellation and refund rules carefully before making a booking.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">1.
                    Order & Booking Cancellation</h3>
                <p><strong>Advance Token Bookings:</strong> If you have paid an advance token to hold a puppy, you may
                    cancel your booking within <strong>24 hours</strong> for a full refund. After 24 hours, the token
                    amount becomes non-refundable, as the puppy was held exclusively for you, turning away other
                    potential families.</p>
                <p><strong>Cancellation Before Dispatch/Pickup:</strong> If full payment is made but the puppy has not
                    yet been dispatched or picked up, you can cancel within 24 hours (excluding the non-refundable token
                    amount).</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">2.
                    Cancellation After Handover</h3>
                <p>Once a puppy has been handed over to you or dispatched via pet transport, <strong>the booking cannot
                        be cancelled</strong>. Moving a puppy back and forth causes severe stress and exposes them to
                    health risks, which goes against our ethical breeding and care guidelines.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">3.
                    Health Guarantee & Replacement Policy</h3>
                <p>We provide 100% vet-checked and vaccinated puppies. We do not accept returns for "change of mind" or
                    "allergic reactions" in the family. However, we offer support in the following rare case:</p>
                <ul style="padding-left: 20px; margin-bottom: 20px;">
                    <li style="margin-bottom:10px;">If a licensed veterinarian diagnoses the puppy with a pre-existing,
                        life-threatening genetic defect within <strong>7 days</strong> of purchase (written vet proof
                        required), we will provide a replacement puppy of the same breed and value.</li>
                </ul>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">4.
                    Refund Timeline</h3>
                <p>Approved refunds (for valid cancellations within 24 hours) will be processed and credited back to the
                    original payment method within <strong>5 to 7 business days</strong>.</p>
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
                    <a href="shipping-return.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Shipping
                        Policy →</a>
                    <a href="terms-condition.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Terms
                        & Conditions →</a>
                </div>
            </div>

            <div
                style="background:linear-gradient(145deg, var(--navy, #0f172a) 0%, #1e293b 100%); border-radius:20px; padding:28px 24px; color:#fff; text-align:center;">
                <h4 style="color:#fff; font-size:18px; font-weight:800; margin-bottom:10px;">Need Assistance?</h4>
                <p style="color:#cbd5e1; font-size:14px; margin-bottom:20px;">Contact
                    <?= h($site['owner'] ?? 'Mr. Nitin') ?> directly for any booking or policy queries.</p>
                <a href="tel:<?= h($tel ?? '') ?>" class="btn btn-teal"
                    style="width:100%; justify-content:center; margin-bottom:10px;">📞
                    <?= h($site['phone'] ?? '') ?></a>
                <a href="contact.php" class="btn btn-line"
                    style="width:100%; justify-content:center; border-color:rgba(255,255,255,0.2); color:#fff;">Message
                    Us</a>
            </div>
        </aside>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>