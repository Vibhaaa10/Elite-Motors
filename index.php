<?php include 'header.php'; ?>

<style>
    :root {
        --theme-bg: #F3F3E0;        /* Beige Background */
        --theme-navy: #133E87;      /* Deep Navy Text/Primary */
        --theme-blue: #608BC1;      /* Accents */
        --theme-light-blue: #CBDCEB; /* Soft Accents */
        --white: #ffffff;
    }

    body {
        background-color: var(--theme-bg);
        color: var(--theme-navy);
        font-family: 'Inter', sans-serif;
    }

    /* Hero Section */
    .hero {
       background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), 
                url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1600');
        background-size: cover;
        background-position: center;
        height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
    }

    /* Car Cards */
    .car-card-vertical {
        background-color: var(--white);
        border: 1px solid var(--theme-light-blue);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: row; 
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(19, 62, 135, 0.05);
    }

    .car-card-vertical:hover {
        transform: scale(1.01);
        box-shadow: 0 15px 35px rgba(19, 62, 135, 0.1);
        border-color: var(--theme-blue);
    }

    .car-image-container {
        width: 40%;
        min-height: 250px;
    }

    .car-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .car-info-container {
        width: 60%;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .price-text {
        color: var(--theme-blue);
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    /* Buttons */
    .btn-buy {
        background-color: var(--theme-navy);
        color: var(--theme-bg);
        font-weight: bold;
        border-radius: 50px;
        padding: 10px 35px;
        border: none;
        transition: 0.3s;
        width: fit-content;
    }

    .btn-buy:hover {
        background-color: var(--theme-blue);
        color: var(--white);
        transform: translateY(-2px);
    }

    .btn-view-all {
        border: 2px solid var(--theme-navy);
        color: var(--theme-navy);
        font-weight: 800;
        border-radius: 50px;
        padding: 15px 40px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view-all:hover {
        background-color: var(--theme-navy);
        color: var(--theme-bg);
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--theme-navy);
        margin-bottom: 40px;
        border-bottom: 3px solid var(--theme-light-blue);
        display: inline-block;
        padding-bottom: 10px;
    }
</style>

<div class="hero">
    <div class="container">
        <h1 class="display-2 fw-bold mb-3">Drive Your Destiny</h1>
        <p class="lead fs-4 mb-4 opacity-75">Curating the world's most exceptional automotive masterpieces.</p>
        <a href="products.php" class="btn btn-buy btn-lg shadow px-5">Explore Collection</a>
    </div>
</div>

<div class="container my-5 py-5">
    <h2 class="section-title">Featured Masterpieces</h2>
    
    <div class="row">
        <?php
        // Fetch 4 featured cars
        $stmt = $pdo->query("SELECT * FROM cars LIMIT 4");
        while($car = $stmt->fetch()): ?>
            <div class="col-12">
                <div class="car-card-vertical shadow-sm">
                    <div class="car-image-container">
                        <img src="<?= $car['image_url'] ?>" alt="<?= htmlspecialchars($car['make']) ?>">
                    </div>
                    
                    <div class="car-info-container">
                        <h3 class="fw-bold mb-1"><?= htmlspecialchars($car['make']) ?></h3>
                        <p class="text-uppercase tracking-wider text-muted small mb-2"><?= htmlspecialchars($car['model']) ?></p>
                        
                        <p class="price-text">₹<?= number_format($car['price']) ?></p>
                        
                        <p class="text-muted small mb-4">
                            Own a piece of automotive excellence. This <?= htmlspecialchars($car['make']) ?> 
                            represents the pinnacle of luxury, performance, and engineering.
                        </p>

                        <div class="mt-auto">
                            <a href="buy.php?id=<?= $car['id'] ?>" class="btn btn-buy">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="text-center mt-5">
        <a href="products.php" class="btn-view-all shadow-sm">
            View All Masterpieces <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<div class="py-5"></div>

</body>
</html>xml_error_string