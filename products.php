<?php include 'header.php'; ?>


<style>
    :root {
        /* Palette Integration */
        --theme-bg: #F3F3E0;        /* Beige Background */
        --theme-navy: #133E87;      /* Primary Navy */
        --theme-blue: #608BC1;      /* Accent Blue */
        --theme-light-blue: #CBDCEB; /* Soft Highlights */
        --white: #ffffff;
    }

    body {
        background-color: var(--theme-bg);
        color: var(--theme-navy);
        font-family: 'Inter', sans-serif;
    }

    .luxury-title {
        font-family: 'Playfair Display', serif;
        color: var(--theme-navy);
        font-weight: 800;
    }

    /* Clean Grid Search Bar */
    .search-wrap {
        max-width: 500px;
        margin: 0 auto;
    }

    .input-group {
        border-radius: 50px;
        overflow: hidden;
        border: 2px solid var(--theme-light-blue);
        background: var(--white);
        box-shadow: 0 5px 15px rgba(19, 62, 135, 0.05);
    }

    .input-group-text {
        background-color: var(--white) !important;
        border: none !important;
        color: var(--theme-blue) !important;
    }

    #carSearch {
        border: none !important;
        padding: 12px;
        color: var(--theme-navy) !important;
    }

    /* Pearl White Grid Cards */
    .car-card {
        background-color: var(--white) !important;
        border: 1px solid var(--theme-light-blue) !important;
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.4s ease;
        height: 100%;
    }

    .car-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(19, 62, 135, 0.15) !important;
        border-color: var(--theme-blue) !important;
    }

    .car-card img {
        height: 240px;
        object-fit: cover;
    }

    .price-tag {
        color: var(--theme-navy);
        font-weight: 800;
        font-size: 1.6rem;
    }

    /* Elegant Theme Button */
    .btn-luxury {
        background-color: var(--theme-navy);
        color: var(--theme-bg) !important;
        font-weight: 600;
        text-transform: uppercase;
        border: none;
        border-radius: 50px;
        padding: 12px;
        transition: 0.3s;
        letter-spacing: 1px;
    }

    .btn-luxury:hover {
        background-color: var(--theme-blue);
        transform: scale(1.02);
    }

    .badge-theme {
        background-color: var(--theme-light-blue);
        color: var(--theme-navy);
        font-weight: 600;
        padding: 5px 15px;
    }
</style>

<div class="container my-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <h1 class="luxury-title display-4 mb-0">The Showroom</h1>
        </div>
        
        <div class="col-md-6">
            <div class="search-wrap">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="carSearch" class="form-control shadow-none" placeholder="Find by brand or model...">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row" id="inventory">
        <?php
        // Fetch all cars from the database
        $stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
        
        while($car = $stmt->fetch()): 
            $make = htmlspecialchars($car['make']);
            $model = htmlspecialchars($car['model']);
        ?>
            <div class="col-md-4 mb-5 car-item" data-name="<?= strtolower($make) . ' ' . strtolower($model) ?>">
                <div class="card car-card shadow-sm">
                    <img src="<?= $car['image_url'] ?>" class="card-img-top" alt="<?= $make ?>">
                    
                    <div class="card-body d-flex flex-column text-center p-4">
                        <h4 class="fw-bold mb-1" style="color: var(--theme-navy);"><?= $make ?></h4>
                        <h6 class="mb-3 small text-uppercase fw-bold" style="color: var(--theme-blue); letter-spacing: 2px;"><?= $model ?></h6>
                        
                        <p class="text-muted small flex-grow-1 px-2 mb-4">
                            <?= htmlspecialchars($car['description']) ?>
                        </p>
                        
                        <div class="mt-auto">
                            <p class="price-tag mb-3">₹<?= number_format($car['price']) ?></p>
                            <a href="buy.php?id=<?= $car['id'] ?>" class="btn btn-luxury w-100 shadow-sm">
                                View Masterpiece
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
// Live Grid Search Filtering
document.getElementById('carSearch').addEventListener('input', function(e) {
    let filter = e.target.value.toLowerCase().trim();
    let cards = document.querySelectorAll('.car-item');
    
    cards.forEach(card => {
        let name = card.getAttribute('data-name');
        card.style.display = name.indexOf(filter) > -1 ? 'block' : 'none';
    });
});
</script>

</body>
</html>