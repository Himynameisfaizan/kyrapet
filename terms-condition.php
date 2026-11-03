<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'policy';

$title = "Terms & Conditions | Kyra Pet Shop New Delhi";
$desc = "Read the terms and conditions of Kyra Pet Shop for purchasing healthy, purebred, and vaccinated puppies.";
$meta_key = "terms and conditions, pet shop rules, kyra pet shop, buy puppy safely";

require __DIR__ . '/includes/header.php';
if (function_exists('banner')) banner('Terms & Conditions', 'Terms', 'Rules and guidelines ensuring ethical practices and healthy pets.');
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
                <p>Welcome to <strong>Kyra Pet Shop</strong>. By engaging with our website, visiting our shop, or
                    booking a puppy, you agree to abide by the following Terms & Conditions designed to protect both the
                    pet parents and the animals.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">1.
                    Health & Vaccination Guarantee</h3>
                <p>All puppies sold by Kyra Pet Shop are vet-checked and come with their initial vaccinations (as per
                    their age). The original vaccination card and health records are handed over to the buyer at the
                    time of delivery or pickup. It is the buyer's responsibility to continue the vaccination schedule as
                    advised by their local veterinarian.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">2.
                    Lifelong Care Responsibility</h3>
                <p>Bringing a puppy home is a 10-15 year commitment. By purchasing a pet from us, you agree to provide
                    proper nutrition, shelter, medical care, and a loving environment. We reserve the right to refuse a
                    sale if we feel the buyer cannot provide a safe home.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">3.
                    Booking & Availability</h3>
                <p>Puppies are live animals, and their availability changes rapidly. A puppy is only considered "booked"
                    or "reserved" once an advance token payment is received. Verbal confirmations do not guarantee
                    reservation.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">4.
                    Color & Size Variations</h3>
                <p>Photographs and videos shared are of the exact puppy available. However, please note that as puppies
                    grow, their coat color, texture, and physical size will naturally change. We provide estimates based
                    on breed standards, but exact adult size or exact shedding levels cannot be guaranteed.</p>

                <h3 style="color: var(--navy, #0f172a); font-weight: 800; font-size: 22px; margin: 30px 0 15px;">5.
                    Governing Law</h3>
                <p>Any disputes arising from purchases or website usage shall be governed by the laws of India and
                    subject to the jurisdiction of the courts in New Delhi.</p>
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
                    <a href="refund-policy.php"
                        style="padding:10px 14px; background:#f8fafc; border-radius:10px; color:#334155; text-decoration:none; font-weight:600;">Refund
                        Policy →</a>
                </div>
            </div>

            <div
                style="background:linear-gradient(145deg, var(--navy, #0f172a) 0%, #1e293b 100%); border-radius:20px; padding:28px 24px; color:#fff; text-align:center;">
                <h4 style="color:#fff; font-size:18px; font-weight:800; margin-bottom:10px;">Visit Us Before Buying</h4>
                <p style="color:#cbd5e1; font-size:14px; margin-bottom:20px;">We encourage all families to visit our
                    Patel Nagar store, check the records, and meet the puppy before making a decision.</p>
                <a href="contact.php" class="btn btn-teal" style="width:100%; justify-content:center;">Get
                    Directions</a>
            </div>
        </aside>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>