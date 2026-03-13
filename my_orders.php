<?php 
include 'header.php'; 

// 1. Redirect to login if they aren't signed in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// 2. Safety Check: If email is missing, tell them to re-login
if (!isset($_SESSION['email'])) {
    echo "<div class='container my-5 alert alert-warning text-center rounded-4 border-0 shadow-sm'>
            <h4 class='fw-bold text-dark'>Session Error</h4>
            <p class='text-muted'>Your account session expired. Please <a href='logout.php' class='fw-bold text-decoration-none'>Logout</a> and Login again.</p>
          </div>";
    exit();
}

$user_email = $_SESSION['email']; 

// Data fetching remains the same, but we treat the results as completed orders
$stmt = $pdo->prepare("SELECT i.*, c.make, c.model, c.price, c.image_url 
                       FROM inquiries i 
                       JOIN cars c ON i.car_id = c.id 
                       WHERE i.customer_email = ? 
                       ORDER BY i.created_at DESC");
$stmt->execute([$user_email]);
$orders = $stmt->fetchAll();
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

    .luxury-title {
        font-family: 'Playfair Display', serif;
        font-weight: 800;
        color: var(--theme-blue); 
        border-bottom: 3px solid var(--theme-light-blue);
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .table-container {
        background-color: var(--white);
        border: 1px solid var(--theme-light-blue);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(19, 62, 135, 0.05);
    }

    .table thead {
        background-color: var(--theme-navy); 
        color: var(--theme-bg);
    }

    .table thead th {
        border: none;
        padding: 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1.5px;
    }

    .table tbody td {
        padding: 25px 20px;
        border-bottom: 1px solid var(--theme-light-blue);
        color: var(--theme-navy);
    }

    .price-text {
        color: var(--theme-blue); 
        font-weight: 800;
        font-size: 1.2rem;
    }

    .badge-status {
        background-color: rgba(203, 220, 235, 0.4);
        color: var(--theme-navy);
        border: 1px solid var(--theme-blue);
        padding: 8px 18px;
        font-weight: 700;
        border-radius: 50px;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .empty-state {
        background-color: var(--white);
        border: 1px solid var(--theme-light-blue);
        border-radius: 20px;
        padding: 80px 40px;
    }

    .btn-theme {
        background-color: var(--theme-navy);
        color: var(--theme-bg) !important;
        font-weight: 800;
        border-radius: 50px;
        padding: 14px 35px;
        transition: 0.3s;
        border: none;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-theme:hover {
        background-color: var(--theme-blue);
        transform: translateY(-2px);
    }
</style>

<div class="container my-5 py-5">
    <div class="mb-5 text-center text-md-start">
        <h1 class="luxury-title display-5">Your Vehicle Orders</h1>
        <p class="opacity-75 fs-5">Track and view the history of your luxury automotive purchases.</p>
    </div>
    
    <?php if (count($orders) > 0): ?>
        <div class="table-container table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Masterpiece</th>
                        <th>Model</th>
                        <th>Purchase Value</th> <th>Date Purchased</th> <th class="text-center pe-4">Order Status</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $row): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $row['image_url'] ?>" class="rounded-3 me-3 shadow-sm" style="width: 120px; height: 75px; object-fit: cover; border: 1px solid var(--theme-light-blue);">
                                    <div>
                                        <span class="fw-bold d-block fs-5"><?= htmlspecialchars($row['make']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold" style="color: var(--theme-blue);"><?= htmlspecialchars($row['model']) ?></td>
                            <td><span class="price-text">₹<?= number_format($row['price']) ?></span></td>
                            <td class="opacity-75 small fw-bold"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td class="text-center pe-4">
                                <span class="badge badge-status">
                                    <i class="bi bi-box-seam me-2"></i>Paid & Processed </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state text-center shadow-sm">
            <i class="bi bi-cart-x mb-4 d-block" style="font-size: 5rem; color: var(--theme-light-blue);"></i>
            <h3 class="fw-bold mb-3">No Orders Found</h3>
            <p class="opacity-75 mb-4">You haven't purchased any masterpieces from our collection yet.</p>
            <a href="products.php" class="btn btn-theme">Explore Showroom</a>
        </div>
    <?php endif; ?>
</div>

<div class="py-5"></div>

</body>
</html>