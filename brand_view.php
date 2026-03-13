<?php include 'header.php'; 

$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$stmt = $pdo->prepare("SELECT * FROM cars WHERE make = ? ORDER BY id DESC");
$stmt->execute([$brand]);
$cars = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--theme-navy);">
                <?= htmlspecialchars($brand) ?> <span style="color: var(--theme-blue)">Collection</span>
            </h1>
            <div class="mx-auto bg-primary mt-2" style="height: 3px; width: 60px;"></div>
        </div>
    </div>

    <div class="row">
        <?php if(empty($cars)): ?>
            <div class="col-12 text-center p-5 opacity-50">No masterpieces currently in stock for this brand.</div>
        <?php else: ?>
            <?php foreach($cars as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="background: white; border-radius: 20px; overflow: hidden;">
                        <img src="<?= $car['image_url'] ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                        <div class="card-body text-center p-4">
                            <h4 class="fw-bold mb-1" style="color: var(--theme-navy);"><?= htmlspecialchars($car['make']) ?></h4>
                            <h6 class="mb-3 small text-uppercase text-muted"><?= htmlspecialchars($car['model']) ?></h6>
                            <p class="fw-bold fs-4 mb-3" style="color: var(--theme-navy);">₹<?= number_format($car['price']) ?></p>
                            <a href="buy.php?id=<?= $car['id'] ?>" class="btn w-100 rounded-pill py-2" style="background-color: var(--theme-navy); color: white;">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>