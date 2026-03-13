<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Motors | Luxury E-commerce</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --theme-beige: #F3F3E0;    /* Background / Light Text */
            --theme-navy: #133E87;     /* Primary Navbar */
            --theme-blue: #608BC1;     /* Accent Blue */
            --theme-ice: #CBDCEB;      /* Hover states / Borders */
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--theme-beige); 
        }

        /* Luxury Navbar Styling */
        .navbar-custom {
            background-color: var(--theme-navy) !important;
            padding: 15px 0;
            border-bottom: 3px solid var(--theme-blue);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            letter-spacing: 2px;
            color: var(--theme-beige) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--theme-ice) !important;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            transition: 0.3s;
            margin: 0 10px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--theme-beige) !important;
            transform: translateY(-2px);
        }

        /* BRAND DROPDOWN STYLING */
        .dropdown-menu {
            background-color: var(--theme-navy);
            border: 1px solid var(--theme-blue);
            border-radius: 12px;
            margin-top: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .dropdown-item {
            color: var(--theme-ice) !important;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 10px 20px;
            transition: 0.3s;
        }

        .dropdown-item:hover {
            background-color: var(--theme-blue) !important;
            color: var(--theme-navy) !important;
            padding-left: 25px;
        }

        .dropdown-toggle::after {
            vertical-align: middle;
            color: var(--theme-blue);
        }

        /* Order Highlight Styling */
        .nav-order-highlight {
            color: var(--theme-blue) !important;
            border: 1px solid var(--theme-blue);
            border-radius: 50px;
            padding: 5px 15px !important;
        }

        .nav-order-highlight:hover {
            background-color: var(--theme-blue);
            color: var(--theme-navy) !important;
        }

        /* Admin Tag - Matches Pill Style */
        .admin-link {
            background-color: var(--theme-blue);
            color: white !important;
            border-radius: 50px;
            padding: 5px 15px !important;
            font-size: 0.75rem;
        }

        .btn-logout {
            color: var(--theme-ice);
            border: 1px solid var(--theme-ice);
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .btn-logout:hover {
            background-color: #ff4d4d;
            border-color: #ff4d4d;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            ELITE <span style="color: var(--theme-blue)">MOTORS</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php 
                // Determine current page
                $current_page = basename($_SERVER['PHP_SELF']); 

                // ONLY show site links if we are NOT on the admin page
                if ($current_page !== 'admin.php'): 
                ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Brands
                        </a>
                        <ul class="dropdown-menu border-0 shadow" aria-labelledby="brandsDropdown">
                            <?php
                            try {
                                $brands = $pdo->query("SELECT DISTINCT make FROM cars ORDER BY make ASC")->fetchAll();
                                foreach($brands as $b): 
                                    $bName = htmlspecialchars($b['make']);
                                    $logoPath = "https://www.carlogos.org/car-logos/" . strtolower($bName) . "-logo.png";
                            ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="brand_view.php?brand=<?= $bName ?>">
                                    <img src="<?= $logoPath ?>" width="20" class="me-2" onerror="this.style.display='none'">
                                    <?= strtoupper($bName) ?>
                                </a>
                            </li>
                            <?php 
                                endforeach; 
                            } catch (Exception $e) {
                                echo "<li><a class='dropdown-item' href='#'>No Brands Available</a></li>";
                            }
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="products.php">Showroom</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Inquiry</a></li>
                <?php endif; ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                    
                    <?php if($_SESSION['role'] !== 'admin' && $current_page !== 'admin.php'): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-order-highlight" href="my_orders.php">
                                <i class="bi bi-bag-check-fill me-1"></i> My Orders
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link admin-link fw-bold" href="admin.php">
                                <i class="bi bi-speedometer2 me-1"></i> ADMIN
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="btn btn-logout px-3 rounded-pill" href="logout.php">LOGOUT</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="signin.php">Sign In</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    