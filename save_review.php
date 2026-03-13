<?php
include 'db.php'; // Ensure this file correctly connects to your 'database'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use the exact keys from your JavaScript FormData
    $name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : 'Valued Customer';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    $text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

    try {
        // Insert into the table created in step 1
        $stmt = $pdo->prepare("INSERT INTO reviews (customer_name, rating, review_text) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$name, $rating, $text])) {
            // success.php expects this exact string to hide the form
            echo "success"; 
        } else {
            echo "failure";
        }
    } catch (PDOException $e) {
        // This will trigger the "There was an issue..." alert
        echo "error";
    }
}
?>