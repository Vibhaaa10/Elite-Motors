<?php 
include 'header.php'; 

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT); 

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$user, $email, $hashed_pass]);
        $msg = "<div class='alert alert-success border-0 shadow-sm rounded-pill px-4 text-center py-2 small'>Registration successful! <a href='signin.php' class='fw-bold' style='color:var(--theme-navy)'>Login here</a></div>";
    } catch (PDOException $e) {
        $msg = "<div class='alert alert-danger border-0 shadow-sm rounded-pill px-4 text-center py-2 small'>Email already exists or error occurred.</div>";
    }
}
?>

<style>
    :root {
        /* Palette Integration */
        --theme-bg: #F3F3E0;        /* Beige Background */
        --theme-navy: #133E87;      /* Primary Navy */
        --theme-blue: #608BC1;      /* Accent Blue */
        --theme-light-blue: #CBDCEB; /* Soft Accents */
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
        color: var(--theme-navy);
    }

    /* Registration Card Styling */
    .register-card {
        background-color: var(--white) !important;
        border: 1px solid var(--theme-light-blue) !important;
        border-radius: 25px !important;
        box-shadow: 0 20px 50px rgba(19, 62, 135, 0.1) !important;
    }

    /* Input Styling */
    .form-label {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--theme-blue); /* #608BC1 */
    }

    .form-control {
        background-color: var(--theme-bg) !important;
        border: 1px solid var(--theme-light-blue) !important;
        border-radius: 12px !important;
        padding: 12px 15px;
        color: var(--theme-navy) !important;
    }

    .form-control:focus {
        border-color: var(--theme-blue) !important;
        box-shadow: 0 0 0 0.25rem rgba(96, 139, 193, 0.1);
    }

    /* Button Styling */
    .btn-luxury {
        background-color: var(--theme-navy);
        color: var(--theme-bg) !important;
        font-weight: 800;
        border-radius: 50px;
        padding: 14px;
        transition: 0.3s;
        border: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.9rem;
    }

    .btn-luxury:hover {
        background-color: var(--theme-blue);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(19, 62, 135, 0.2);
    }

    .link-luxury {
        color: var(--theme-blue);
        text-decoration: none;
        font-weight: 700;
        transition: 0.2s;
    }

    .link-luxury:hover {
        color: var(--theme-navy);
        text-decoration: underline;
    }
</style>

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card register-card p-5 shadow-lg" style="width: 480px; border: none;">
        <div class="text-center mb-4">
            <h2 class="luxury-title mb-2">Join the Elite</h2>
            <p class="text-muted small">Create your account to start your journey.</p>
        </div>

        <div class="mb-3">
            <?= $msg ?>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="username" class="form-control shadow-none" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control shadow-none" placeholder="example@gmail.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-luxury w-100 shadow-sm">Become a Member</button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-muted small mb-0">Already a member? 
                <a href="signin.php" class="link-luxury ms-1">Sign In </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>