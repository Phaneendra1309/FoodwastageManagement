<?php
// donor/dashboard.php - Donor Dashboard
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../auth/login.php");
    exit();
}
include '../database/db_connect.php';

// Fetch statistics
$user_id = $_SESSION['user_id'];
$totalDonations = $conn->query("SELECT COUNT(*) AS count FROM donations WHERE user_id = $user_id")->fetch_assoc()['count'];
$pendingRequests = $conn->query("SELECT COUNT(*) AS count FROM requests WHERE donation_id IN (SELECT id FROM donations WHERE user_id = $user_id) AND status='pending'")->fetch_assoc()['count'];

// Fetch donation category data for chart
$donationData = $conn->query("SELECT food_item, COUNT(*) AS count FROM donations WHERE user_id = $user_id GROUP BY food_item");
$foodItems = [];
$donationCounts = [];
while ($row = $donationData->fetch_assoc()) {
    $foodItems[] = $row['food_item'];
    $donationCounts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Donor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include '../includes/topbar.php'; ?>
    <div id="layoutSidenav">
        <?php include '../includes/sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Donor Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Total Donations: <?php echo $totalDonations; ?></div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Pending Requests: <?php echo $pendingRequests; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Donations by Food Type
                                </div>
                                <div class="card-body">
                                    <canvas id="donationChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include '../includes/footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('donationChart').getContext('2d');
        var donationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($foodItems); ?>,
                datasets: [{
                    label: 'Number of Donations',
                    data: <?php echo json_encode($donationCounts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>