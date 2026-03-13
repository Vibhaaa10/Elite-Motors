<?php 
// 1. Include the header (which includes your database connection)
include 'header.php'; 

// 2. Safely get the data passed from the buy.php page
$customer_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Valued Customer';
$car_name = isset($_GET['car']) ? htmlspecialchars($_GET['car']) : 'your selected vehicle';
?>

<style>
    :root {
        --theme-bg: #F3F3E0;
        --theme-navy: #133E87;
        --theme-blue: #608BC1;
        --theme-light-blue: #CBDCEB;
    }

    body { background-color: var(--theme-bg); }

    .success-card, .review-card {
        background: #ffffff;
        border-radius: 25px;
        border: 1px solid var(--theme-light-blue) !important;
        height: 100%; /* Ensures equal height side-by-side */
    }

    /* Star Rating System Styling */
    .rating-wrapper {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .rating-wrapper input { display: none; }

    .rating-wrapper label {
        cursor: pointer;
        width: 35px;
        height: 35px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23CBDCEB' viewBox='0 0 16 16'%3E%3Cpath d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
        transition: 0.2s;
    }

    .rating-wrapper input:checked ~ label,
    .rating-wrapper label:hover,
    .rating-wrapper label:hover ~ label {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23608BC1' viewBox='0 0 16 16'%3E%3Cpath d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/%3E%3C/svg%3E");
    }

    .btn-luxury {
        background-color: var(--theme-navy);
        color: white !important;
        border-radius: 50px;
        padding: 12px 25px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .btn-luxury:hover {
        background-color: var(--theme-blue);
        transform: translateY(-2px);
    }
</style>

<div class="container my-5 py-5">
    <div class="row g-4 align-items-stretch">
        
        <div class="col-lg-6">
            <div class="success-card shadow-lg p-5 text-center h-100">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                
                <h1 class="fw-bold text-dark mb-3">Order Received!</h1>
                <p class="text-muted mb-4">
                    Thank you, <strong><?= $customer_name ?></strong>. Your inquiry for the <strong><?= $car_name ?></strong> is logged.
                </p>
                
                <div class="bg-light p-4 rounded-4 mb-4 text-start border-start border-primary border-4">
                    <h6 class="fw-bold mb-2">Next Steps:</h6>
                    <ul class="small mb-0 ps-3">
                        <li class="mb-1">Consultant review of your selection.</li>
                        <li class="mb-1">Contact within 24 business hours.</li>
                        <li>Vehicle prioritized for your inquiry.</li>
                    </ul>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-auto">
                    <a href="index.php" class="btn btn-outline-dark btn-sm px-4 rounded-pill fw-bold">Home</a>
                    <a href="products.php" class="btn btn-primary btn-sm px-4 shadow rounded-pill fw-bold">Showroom</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="review-card shadow-lg p-5 text-center h-100">
                <h3 class="fw-bold text-dark mb-2">Client Feedback</h3>
                <p class="text-muted small mb-4">Help us maintain our standards by sharing your thoughts on the <strong><?= $car_name ?></strong> acquisition process.</p>
                
                <form id="reviewForm">
                    <div class="rating-wrapper mb-4">
                        <input type="radio" name="rating" id="star5" value="5"><label for="star5"></label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4"></label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3"></label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2"></label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1"></label>
                    </div>

                    <div class="mb-4">
                        <textarea class="form-control bg-light border-0 p-3 shadow-none rounded-4 small" rows="4" placeholder="Your experience..."></textarea>
                    </div>

                    <button type="button" onclick="submitReview()" class="btn btn-luxury w-100">Submit Review</button>
                </form>

                <div id="reviewStatus" class="mt-5 fw-bold text-success d-none">
                    <i class="bi bi-stars fs-1 d-block mb-3"></i>
                    Thank you for your feedback!
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function submitReview() {
    // 1. Capture the rating from the radio buttons (defaults to 5 if none checked)
    const ratingElement = document.querySelector('input[name="rating"]:checked');
    const rating = ratingElement ? ratingElement.value : 5; 
    
    // 2. Capture the user's feedback text
    const reviewText = document.querySelector('textarea').value;
    
    // 3. Capture the name from the PHP variable defined at the top of your page
    const customerName = "<?= $customer_name ?>"; 

    // Validation: Ensure the user typed something
    if (reviewText.trim() === "") {
        alert("Please share a few words about your experience before submitting.");
        return;
    }

    // 4. Create a FormData object to send the values to PHP
    const formData = new FormData();
    formData.append('customer_name', customerName);
    formData.append('rating', rating);
    formData.append('review_text', reviewText);

    // 5. Send the data to 'save_review.php' using Fetch API
    fetch('save_review.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // 6. If PHP returns "success", update the UI
        if (data.trim() === "success") {
            document.getElementById('reviewForm').classList.add('d-none');
            document.getElementById('reviewStatus').classList.remove('d-none');
        } else {
            alert("There was an issue saving your review. Please try again.");
        }
    })
    .catch(error => {
        console.error('Submission Error:', error);
    });
}
</script>