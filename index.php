<?php
$conn = new mysqli('localhost', 'root', '', 'retail_db');
// Get total sales per month for the last 6 months
$sales_data = $conn->query("SELECT DATE_FORMAT(date, '%b %Y') as month, SUM(total) as total FROM sales GROUP BY month ORDER BY MIN(date) DESC LIMIT 6");
$labels = [];
$totals = [];
$total_sales = $conn->query("SELECT SUM(total) as total FROM sales")->fetch_assoc()['total'] ?? 0;
$num_sales = $conn->query("SELECT COUNT(*) as count FROM sales")->fetch_assoc()['count'] ?? 0;
$num_customers = $conn->query("SELECT COUNT(DISTINCT customer) as count FROM sales")->fetch_assoc()['count'] ?? 0;
$sales_by_customer = $conn->query("SELECT customer, COUNT(*) as num_sales, SUM(total) as total FROM sales GROUP BY customer ORDER BY total DESC");
$sales_by_month = $conn->query("SELECT DATE_FORMAT(date, '%b %Y') as month, COUNT(*) as num_sales, SUM(total) as total FROM sales GROUP BY month ORDER BY MIN(date) DESC LIMIT 12");
while ($row = $sales_data->fetch_assoc()) {
  array_unshift($labels, $row['month']); 
  array_unshift($totals, $row['total']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Retail Shop Admin Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container-fluid" style="margin-left:250px; padding-top: 30px;">
  <h1 class="mb-4">Dashboard</h1>
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-bg-success mb-3">
        <div class="card-body">
          <h5 class="card-title">Total Sales</h5>
          <p class="card-text fs-4">$<?= number_format($total_sales, 2) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-primary mb-3">
        <div class="card-body">
          <h5 class="card-title">Number of Sales</h5>
          <p class="card-text fs-4"><?= $num_sales ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <section id="overview" class="text-center">
        <h2>Sales Overview</h2>
        <canvas id="salesChart" style="max-width:100%; height:300px;"></canvas>
      </section>
    </div>
  </div>
</div>
<script>
  const salesLabels = <?php echo json_encode($labels); ?>;
  const salesTotals = <?php echo json_encode($totals); ?>;
  const ctx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: salesLabels,
      datasets: [{
        label: 'Total Sales',
        data: salesTotals,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.1)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true, position: 'top' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
