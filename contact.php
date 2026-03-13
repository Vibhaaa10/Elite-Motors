<?php 
// 1. Include header and initialize database connection
include 'header.php'; 

// 2. BACKEND LOGIC: Handle the Inquiry Submission
$message_status = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_inquiry'])) {
    // Collect data from the form
    $name = $_POST['full_name'];
    $purpose = $_POST['purpose'];
    $message = $_POST['message'];
    
    // Check if user is logged in
    $email = $_SESSION['email'] ?? 'Guest';
    $phone = "N/A"; 

    try {
        // We explicitly set car_id to NULL for general inquiries
        $stmt = $pdo->prepare("INSERT INTO inquiries (customer_name, customer_email, customer_phone, message, car_id, created_at) VALUES (?, ?, ?, ?, NULL, NOW())");
        
        if ($stmt->execute([$name, $email, $phone, "[$purpose] " . $message])) {
            $message_status = "sent";
        }
    } catch (PDOException $e) {
        // If this runs, the database setting is still incorrect
        $message_status = "error";
    }
}
?>

<style>
    :root {
        --theme-bg: #F3F3E0; --theme-navy: #133E87; --theme-blue: #608BC1; 
        --theme-light-blue: #CBDCEB; --white: #ffffff;
    }
    body { background-color: var(--theme-bg); color: var(--theme-navy); font-family: 'Inter', sans-serif; }
    .luxury-title { font-family: 'Playfair Display', serif; color: var(--theme-navy); font-weight: 800; }
    .contact-card { background-color: var(--white) !important; border: 1px solid var(--theme-light-blue) !important; border-radius: 20px !important; box-shadow: 0 15px 35px rgba(19, 62, 135, 0.08) !important; }
    .form-label { font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--theme-blue); }
    .form-control { background-color: var(--theme-bg) !important; border: 1px solid var(--theme-light-blue) !important; border-radius: 12px !important; padding: 14px; }
    .btn-theme { background-color: var(--theme-navy); color: var(--theme-bg) !important; font-weight: 700; border-radius: 50px; padding: 15px; border: none; text-transform: uppercase; letter-spacing: 2px; }
    .btn-theme:hover { background-color: var(--theme-blue); transform: translateY(-3px); }
</style>

<div class="container my-5 py-5">
    <?php if($message_status == "error"): ?>
        <div class="alert alert-danger text-center">
            <strong>Database Sync Error:</strong> The 'inquiries' table is still blocking empty car IDs. 
            <br>Please run the SQL command: <code>ALTER TABLE inquiries MODIFY car_id INT(11) NULL;</code> in phpMyAdmin.
        </div>
    <?php endif; ?>

    <div class="text-center mb-5">
        <h1 class="luxury-title display-4">Submit Your Inquiry</h1>
        <p class="fs-5 opacity-75">Speak with our specialists about acquiring your next masterpiece.</p>
    </div>

    <div class="row g-5">
        <div class="col-md-6">
            <div class="contact-card p-5 shadow-sm">
                <form id="contactForm" method="POST">
                    <div class="mb-4">
                        <label class="form-label">Purpose of Inquiry</label>
                        <select name="purpose" class="form-select shadow-none p-3 rounded-3">
                            <option>Acquisition & Pricing</option>
                            <option>Private Viewing Session</option>
                            <option>Trade-In Valuation</option>
                            <option>Logistics & Delivery</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control shadow-none" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Inquiry Message</label>
                        <textarea name="message" class="form-control shadow-none" rows="4" placeholder="How can we assist you today?" required></textarea>
                    </div>
                    <button type="submit" name="submit_inquiry" class="btn btn-theme w-100">Send Inquiry</button>
                </form>
            </div>
        </div>

        <div class="col-md-6 d-flex flex-column justify-content-center text-center text-md-start">
            <div class="ps-md-5">
                <div class="mb-5">
                    <i class="bi bi-geo-alt-fill text-primary fs-2"></i>
                    <h5 class="fw-bold mt-2">Global Headquarters</h5>
                    <p class="opacity-75">G-Block, Platina Building, Bandra Kurla Complex, Mumbai, Maharashtra 400051.</p>
                </div>
                <div class="mb-5">
                    <i class="bi bi-telephone-fill text-primary fs-2"></i>
                    <h5 class="fw-bold mt-2">VIP Liaison</h5>   
                    <p class="opacity-75">+91 22 6123 4567</p>
                </div>
                <div>
                    <i class="bi bi-envelope-open-fill text-primary fs-2"></i>
                    <h5 class="fw-bold mt-2">Digital Showroom</h5>
                    <p class="opacity-75">concierge@elitemotors.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php if($message_status == "sent"): ?>
    alert("Inquiry Sent Successfully!");
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
<?php endif; ?>
</script>
</body>
</html>