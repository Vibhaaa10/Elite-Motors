<?php 
// 1. PLACE ALL REDIRECT LOGIC BEFORE ANY HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include 'db.php'; 

// SECURITY: Redirect to login if they aren't signed in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// VALIDATE INPUT ID
if (!isset($_GET['id']) || empty($_GET['id'])) { 
    header("Location: products.php"); 
    exit(); 
}

$car_id = (int)$_GET['id'];

// FETCH CAR DETAILS
try {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();

    if (!$car) {
        header("Location: products.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database Error");
}

// HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $msg   = htmlspecialchars(trim($_POST['message']));

    try {
        $sql = "INSERT INTO inquiries (car_id, customer_name, customer_email, customer_phone, message) VALUES (?, ?, ?, ?, ?)";
        $insert = $pdo->prepare($sql);
        
        if ($insert->execute([$car_id, $name, $email, $phone, $msg])) {
            // Success: Redirect to success page
            $success_url = "success.php?name=" . urlencode($name) . "&car=" . urlencode($car['make'] . ' ' . $car['model']);
            header("Location: " . $success_url);
            exit(); 
        }
    } catch (PDOException $e) {
        $error_msg = "Order Failed: System error.";
    }
}

// 2. NOW START THE HTML OUTPUT
include 'header.php'; 
?>

<style>
    :root {
        --theme-bg: #F3F3E0;
        --theme-navy: #133E87;
        --theme-blue: #608BC1;
        --theme-light-blue: #CBDCEB;
        --white: #ffffff;
    }

    body {
        background-color: var(--theme-bg);
        color: var(--theme-navy);
        font-family: 'Inter', sans-serif;
    }

    .checkout-card {
        background-color: var(--white);
        border: 1px solid var(--theme-light-blue);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(19, 62, 135, 0.08);
    }

    .luxury-title {
        font-family: 'Playfair Display', serif;
        font-weight: 800;
        color: var(--theme-navy);
    }

    .form-label {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--theme-blue);
    }

    .form-control {
        background-color: var(--theme-bg) !important;
        border: 1px solid var(--theme-light-blue) !important;
        border-radius: 12px !important;
        padding: 12px;
        color: var(--theme-navy) !important;
    }

    .form-control:focus {
        border-color: var(--theme-blue) !important;
        box-shadow: 0 0 0 0.25rem rgba(96, 139, 193, 0.15);
    }

    .price-text {
        color: var(--theme-blue);
        font-weight: 800;
    }

    .btn-buy {
        background-color: var(--theme-navy);
        color: var(--theme-bg) !important;
        font-weight: 800;
        border-radius: 50px;
        padding: 15px;
        border: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
    }

    .btn-buy:hover {
        background-color: var(--theme-blue);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(19, 62, 135, 0.2);
    }

    .badge-selection {
        background-color: var(--theme-light-blue);
        color: var(--theme-navy);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
    }
</style>

<div class="container my-5 py-4">
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger rounded-pill px-4 shadow-sm mb-4"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="row justify-content-center g-5">
        <div class="col-md-4">
            <div class="checkout-card overflow-hidden">
                <img src="<?= $car['image_url'] ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= $car['make'] ?>">
                <div class="card-body p-4 text-center">
                    <span class="badge badge-selection rounded-pill mb-3 px-3 py-2">Reserved for Acquisition</span>
                    <h2 class="luxury-title mb-1"><?= htmlspecialchars($car['make']) ?></h2>
                    <h6 class="text-muted text-uppercase tracking-widest mb-4 small"><?= htmlspecialchars($car['model']) ?></h6>
                    <div class="border-top pt-3">
                        <p class="mb-1 opacity-75 small">Total Investment</p>
                        <h3 class="price-text fs-2">₹<?= number_format($car['price']) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="checkout-card p-5">
                <h2 class="luxury-title mb-2">Finalize Your Inquiry</h2>
                <p class="opacity-75 mb-5">Confirm your contact information to initiate the acquisition process with our concierge.</p>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control shadow-none" placeholder="Enter your name" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control shadow-none" value="<?= $_SESSION['email'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="phone" class="form-control shadow-none" placeholder="+1 (555) 000-0000" required>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Acquisition Notes</label>
                        <textarea name="message" class="form-control shadow-none" rows="3" placeholder="Financing, trade-in, or global delivery requests..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-buy w-100 shadow-sm">Authorize Inquiry & Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="py-5"></div>

</body>
</html>