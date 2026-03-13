<?php 
// 1. DATABASE & SESSION (Absolute Top)
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include 'db.php'; 

// 2. SECURITY: Check before any HTML output to avoid header errors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

// 3. BACKEND ACTIONS
// --- Add Car Logic ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_car'])) {
    $stmt = $pdo->prepare("INSERT INTO cars (make, model, price, description, image_url) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$_POST['make'], $_POST['model'], $_POST['price'], $_POST['description'], $_POST['image_url']])) {
        header("Location: admin.php?panel=masterpieces&msg=added");
        exit();
    }
}

// --- Update Car Logic (EDIT) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_car'])) {
    $stmt = $pdo->prepare("UPDATE cars SET make=?, model=?, price=?, description=?, image_url=? WHERE id=?");
    if ($stmt->execute([$_POST['make'], $_POST['model'], $_POST['price'], $_POST['description'], $_POST['image_url'], $_POST['car_id']])) {
        header("Location: admin.php?panel=masterpieces&status=updated");
        exit();
    }
}

// Delete Car Logic
if (isset($_GET['delete_id'])) {
    $pdo->prepare("DELETE FROM cars WHERE id = ?")->execute([$_GET['delete_id']]);
    header("Location: admin.php?panel=masterpieces&msg=deleted");
    exit();
}

// Delete Review Logic
if (isset($_GET['delete_review'])) {
    $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$_GET['delete_review']]);
    header("Location: admin.php?panel=reviews&msg=deleted");
    exit();
}

// 4. DATA FETCHING
$cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll();
$orders = $pdo->query("SELECT i.*, c.make, c.model, c.price FROM inquiries i JOIN cars c ON i.car_id = c.id ORDER BY i.created_at DESC")->fetchAll();
$users = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll();
$inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();

try { $reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll(); } catch (PDOException $e) { $reviews = []; }

// 5. START HTML OUTPUT
include 'header.php'; 
?>

<style>
    :root {
        --color-bg: #F3F3E0;    
        --color-primary: #133E87; 
        --color-secondary: #608BC1; 
        --color-accent: #CBDCEB;   
        --color-white: #ffffff;
    }
    body { background-color: var(--color-bg); overflow-x: hidden; margin: 0; font-family: 'Inter', sans-serif; }
    .sidebar { height: 100vh; width: 260px; position: fixed; left: 0; top: 0; background-color: var(--color-primary); padding-top: 20px; color: var(--color-bg); z-index: 1000; }
    .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; color: var(--color-primary); }
    .nav-link-admin { color: var(--color-accent); padding: 15px 25px; display: block; text-decoration: none; transition: 0.3s; font-weight: 500; cursor: pointer; border-left: 4px solid transparent; }
    .nav-link-admin:hover, .nav-link-admin.active { background: rgba(255, 255, 255, 0.1); color: var(--color-bg); border-left: 4px solid var(--color-bg); }
    .stat-card { background: var(--color-white); border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(19, 62, 135, 0.1); border-bottom: 3px solid var(--color-secondary); }
    .admin-table-card { background: var(--color-white); border-radius: 12px; box-shadow: 0 4px 15px rgba(19, 62, 135, 0.1); overflow: hidden; margin-bottom: 30px; }
    .table thead { background: var(--color-accent); color: var(--color-primary); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .section-panel { display: none; }
    .section-panel.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .star-filled { color: #ffc107; }
</style>

<div class="sidebar shadow">
    <div class="px-4 mb-5 text-white"><h4>ELITE MOTORS</h4><small>Management Portal</small></div>
    <nav>
        <a onclick="showPanel('dashboard')" id="l-dashboard" class="nav-link-admin active"><i class="bi bi-grid-fill me-3"></i> Dashboard</a>
        <a onclick="showPanel('masterpieces')" id="l-masterpieces" class="nav-link-admin"><i class="bi bi-car-front-fill me-3"></i> Masterpiece Fleet</a>
        <a onclick="showPanel('orders')" id="l-orders" class="nav-link-admin"><i class="bi bi-bag-check-fill me-3"></i> User Orders</a>
        <a onclick="showPanel('inquiries')" id="l-inquiries" class="nav-link-admin"><i class="bi bi-envelope-paper-fill me-3"></i> User Inquiries</a>
        <a onclick="showPanel('users')" id="l-users" class="nav-link-admin"><i class="bi bi-people-fill me-3"></i> Member Directory</a>
        <a onclick="showPanel('reviews')" id="l-reviews" class="nav-link-admin"><i class="bi bi-chat-left-quote-fill me-3"></i> Client Reviews</a>
        <hr class="mx-3 opacity-25">
        <a href="index.php" class="nav-link-admin"><i class="bi bi-arrow-left-circle me-3"></i> Exit Admin</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold mb-0" id="panel-title">Dashboard</h2>
        <input type="text" id="adminSearch" class="form-control rounded-pill px-4 shadow-sm" style="width: 300px;" placeholder="Search data...">
    </div>

    <div id="p-dashboard" class="section-panel active">
        <div class="row g-4 mb-5">
            <div class="col-md-3"><div class="stat-card text-center"><h6>Fleet</h6><h3 class="fw-bold"><?= count($cars) ?></h3></div></div>
            <div class="col-md-3"><div class="stat-card text-center"><h6>Orders</h6><h3 class="fw-bold"><?= count($orders) ?></h3></div></div>
            <div class="col-md-3"><div class="stat-card text-center"><h6>Inquiries</h6><h3 class="fw-bold"><?= count($inquiries) ?></h3></div></div>
            <div class="col-md-3"><div class="stat-card text-center"><h6>Reviews</h6><h3 class="fw-bold"><?= count($reviews) ?></h3></div></div>
        </div>
    </div>

    <div id="p-masterpieces" class="section-panel">
        <div class="admin-table-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0">Masterpiece Fleet</h5>
                <button class="btn btn-navy btn-sm px-4" style="background-color: var(--color-primary); color: white;" data-bs-toggle="modal" data-bs-target="#addCarModal">+ Add Masterpiece</button>
            </div>
            <table class="table align-middle">
                <thead><tr><th class="ps-4">Preview</th><th>Make & Model</th><th>Asset Value</th><th class="text-end pe-4">Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($cars as $c): ?>
                    <tr class="searchable-row">
                        <td class="ps-4"><img src="<?= $c['image_url'] ?>" width="80" class="rounded shadow-sm" style="height: 50px; object-fit: cover;"></td>
                        <td><span class="fw-bold d-block"><?= htmlspecialchars($c['make']) ?></span><small class="text-muted"><?= htmlspecialchars($c['model']) ?></small></td>
                        <td class="fw-bold" style="color:var(--color-primary)"><?= number_format($c['price']) ?></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary me-2" onclick='openEditModal(<?= json_encode($c) ?>)'><i class="bi bi-pencil-square"></i></button>
                            <a href="admin.php?delete_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete vehicle?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-orders" class="section-panel">
        <div class="admin-table-card">
            <div class="p-4 border-bottom"><h5 class="fw-bold m-0">User Orders</h5></div>
            <table class="table align-middle">
                <thead><tr><th class="ps-4">Client</th><th>Vehicle</th><th>Price</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr class="searchable-row">
                        <td class="ps-4"><span class="fw-bold d-block"><?= htmlspecialchars($o['customer_name']) ?></span><small class="text-muted"><?= htmlspecialchars($o['customer_email']) ?></small></td>
                        <td><span class="text-primary fw-semibold d-block"><?= htmlspecialchars($o['make'] . " " . $o['model']) ?></span></td>
                        <td class="fw-bold text-success">$<?= number_format($o['price']) ?></td>
                        <td class="small"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-inquiries" class="section-panel">
        <div class="admin-table-card">
            <div class="p-4 border-bottom"><h5 class="fw-bold m-0">User Inquiries</h5></div>
            <table class="table align-middle">
                <thead><tr><th class="ps-4">Name</th><th>Contact</th><th>Message</th><th>Date</th></tr></thead>
                <tbody>
                    <?php if (empty($inquiries)): ?>
                        <tr><td colspan="4" class="text-center p-5 opacity-50">No inquiries found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inq): ?>
                        <tr class="searchable-row">
                            <td class="ps-4 fw-bold"><?= htmlspecialchars($inq['customer_name']) ?></td>
                            <td><?= htmlspecialchars($inq['customer_email']) ?><br><small><?= htmlspecialchars($inq['customer_phone']) ?></small></td>
                            <td class="small text-wrap" style="max-width:300px;"><?= htmlspecialchars($inq['message'] ?? 'General inquiry') ?></td>
                            <td><?= date('M d, Y', strtotime($inq['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-users" class="section-panel">
        <div class="admin-table-card">
            <div class="p-4 border-bottom"><h5 class="fw-bold m-0">Member Directory</h5></div>
            <table class="table align-middle">
                <thead><tr><th class="ps-4">Username</th><th>Email Address</th><th>Tier</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr class="searchable-row">
                        <td class="ps-4 fw-bold"><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-info-subtle text-info px-3">Elite Member</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="p-reviews" class="section-panel">
        <div class="admin-table-card">
            <div class="p-4 border-bottom"><h5 class="fw-bold m-0">Client Feedback</h5></div>
            <table class="table align-middle">
                <thead><tr><th class="ps-4">Client</th><th>Rating</th><th>Feedback</th><th class="text-end pe-4">Action</th></tr></thead>
                <tbody>
                    <?php foreach ($reviews as $r): ?>
                    <tr class="searchable-row">
                        <td class="ps-4 fw-bold"><?= htmlspecialchars($r['customer_name']) ?></td>
                        <td><?php for($i=1;$i<=5;$i++){ echo '<i class="bi bi-star-fill '.($i<=$r['rating']?'star-filled':'text-muted').'"></i>'; } ?></td>
                        <td class="small">"<?= htmlspecialchars($r['review_text']) ?>"</td>
                        <td class="text-end pe-4"><a href="admin.php?delete_review=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg border-0" style="background: var(--color-bg);">
      <div class="modal-header border-0 bg-primary text-white"><h5 class="modal-title fw-bold">Add New Masterpiece</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST">
        <div class="modal-body p-4">
          <input type="text" name="make" class="form-control mb-3 rounded-pill" placeholder="Make (e.g. Tesla)" required>
          <input type="text" name="model" class="form-control mb-3 rounded-pill" placeholder="Model (e.g. Model S)" required>
          <input type="number" name="price" class="form-control mb-3 rounded-pill" placeholder="Price" required>
          <input type="text" name="image_url" class="form-control mb-3 rounded-pill" placeholder="Image URL" required>
          <textarea name="description" class="form-control rounded-4" rows="3" placeholder="Description" required></textarea>
        </div>
        <div class="modal-footer border-0"><button type="submit" name="add_car" class="btn btn-primary w-100 rounded-pill py-2">Save Masterpiece</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editCarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg border-0" style="background: var(--color-bg);">
      <div class="modal-header border-0 bg-primary text-white"><h5 class="modal-title fw-bold">Edit Masterpiece</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form method="POST">
        <div class="modal-body p-4">
          <input type="hidden" name="car_id" id="edit_car_id">
          <input type="text" name="make" id="edit_make" class="form-control mb-3 rounded-pill" required>
          <input type="text" name="model" id="edit_model" class="form-control mb-3 rounded-pill" required>
          <input type="number" name="price" id="edit_price" class="form-control mb-3 rounded-pill" required>
          <input type="text" name="image_url" id="edit_image_url" class="form-control mb-3 rounded-pill" required>
          <textarea name="description" id="edit_description" class="form-control rounded-4" rows="3" required></textarea>
        </div>
        <div class="modal-footer border-0"><button type="submit" name="update_car" class="btn btn-primary w-100 rounded-pill py-2">Update Masterpiece</button></div>
      </form>
    </div>
  </div>
</div>

<script>
    function openEditModal(car) {
        document.getElementById('edit_car_id').value = car.id;
        document.getElementById('edit_make').value = car.make;
        document.getElementById('edit_model').value = car.model;
        document.getElementById('edit_price').value = car.price;
        document.getElementById('edit_image_url').value = car.image_url;
        document.getElementById('edit_description').value = car.description;
        new bootstrap.Modal(document.getElementById('editCarModal')).show();
    }
    function showPanel(id) {
        document.querySelectorAll('.section-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('p-' + id).classList.add('active');
        document.querySelectorAll('.nav-link-admin').forEach(l => l.classList.remove('active'));
        document.getElementById('l-' + id).classList.add('active');
        let titles = { 'dashboard': 'Dashboard', 'masterpieces': 'Masterpiece Fleet', 'orders': 'User Orders', 'users': 'Member Directory', 'reviews': 'Client Reviews', 'inquiries': 'User Inquiries' };
        document.getElementById('panel-title').innerText = titles[id];
    }
    document.getElementById('adminSearch').addEventListener('input', function(e) {
        let filter = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.searchable-row').forEach(row => { row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none'; });
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function(){
       if(document.getElementById('adminSearch').value === "") {
           window.location.reload();
       }
    }, 30000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>