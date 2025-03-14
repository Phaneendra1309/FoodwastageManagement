<?php
// admin/edit_donation.php - Edit Donation Page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../database/db_connect.php';

// Get donation ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_donations.php");
    exit();
}
$donation_id = $_GET['id'];

// Fetch donation data
$stmt = $conn->prepare("SELECT food_item, quantity, pickup_location, expiry_date, status FROM donations WHERE id = ?");
$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();
$donation = $result->fetch_assoc();
$stmt->close();

// Update donation details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_item = $_POST['food_item'];
    $quantity = $_POST['quantity'];
    $pickup_location = $_POST['pickup_location'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE donations SET food_item = ?, quantity = ?, pickup_location = ?, expiry_date = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $food_item, $quantity, $pickup_location, $expiry_date, $status, $donation_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_donations.php?success=Donation updated successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Donation</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <?php include '../includes/topbar.php'; ?>
    <div id="layoutSidenav">
        <?php include '../includes/sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Donation</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Modify Donation Details</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="food_item" class="form-label">Food Item</label>
                                    <input type="text" class="form-control" id="food_item" name="food_item" value="<?php echo htmlspecialchars($donation['food_item']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($donation['quantity']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pickup_location" class="form-label">Pickup Location</label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" value="<?php echo htmlspecialchars($donation['pickup_location']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($donation['expiry_date']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="available" <?php if ($donation['status'] == 'available') echo 'selected'; ?>>Available</option>
                                        <option value="claimed" <?php if ($donation['status'] == 'claimed') echo 'selected'; ?>>Claimed</option>
                                        <option value="delivered" <?php if ($donation['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                                        <option value="expired" <?php if ($donation['status'] == 'expired') echo 'selected'; ?>>Expired</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
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
