<?php
// donor/donate_food.php - Donate Food Page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../auth/login.php");
    exit();
}
include '../database/db_connect.php';

// Handle donation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_item = $_POST['food_item'];
    $quantity = $_POST['quantity'];
    $pickup_location = $_POST['pickup_location'];
    $expiry_date = $_POST['expiry_date'];

    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO donations (user_id, food_item, quantity, pickup_location, expiry_date, status) VALUES (?, ?, ?, ?, ?, 'available')");
$stmt->bind_param("issss", $user_id, $food_item, $quantity, $pickup_location, $expiry_date);

    $stmt->execute();
    $stmt->close();
    
    $success = "Food donation successfully submitted!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Donate Food</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <?php include '../includes/topbar.php'; ?>
    <div id="layoutSidenav">
        <?php include '../includes/sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Donate Food</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Submit a Food Donation</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="food_item" class="form-label">Food Item</label>
                                    <input type="text" class="form-control" id="food_item" name="food_item" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pickup_location" class="form-label">Pickup Location</label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" required>
                                </div>
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Donation</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include '../includes/footer.php'; ?>
        </div>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>