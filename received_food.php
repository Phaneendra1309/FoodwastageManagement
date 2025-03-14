<?php
// receiver/received_food.php - Received Food Page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: ../auth/login.php");
    exit();
}
include '../database/db_connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT requests.id, donations.food_item, donations.quantity, donations.pickup_location, donations.expiry_date, requests.status, requests.created_at 
        FROM requests 
        JOIN donations ON requests.donation_id = donations.id 
        WHERE requests.user_id = ? AND requests.status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Food</title>
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="../assets/js/script.js"></script>
</head>
<body>
    <div id="wrapper">
        <?php include '../includes/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../includes/topbar.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Received Food</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Food Item</th>
                                        <th>Quantity</th>
                                        <th>Pickup Location</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['food_item']); ?></td>
                                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                            <td><?php echo htmlspecialchars($row['pickup_location']); ?></td>
                                            <td><?php echo $row['expiry_date']; ?></td>
                                            <td><?php echo ucfirst($row['status']); ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>