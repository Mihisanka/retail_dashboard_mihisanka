<?php
$conn = new mysqli('localhost', 'root', '', 'retail_db');
$total_sales = $conn->query("SELECT SUM(total) as total FROM sales")->fetch_assoc()['total'] ?? 0;
$num_sales = $conn->query("SELECT COUNT(*) as count FROM sales")->fetch_assoc()['count'] ?? 0;
$num_customers = $conn->query("SELECT COUNT(DISTINCT customer) as count FROM sales")->fetch_assoc()['count'] ?? 0;
$sales_by_customer = $conn->query("SELECT customer, COUNT(*) as num_sales, SUM(total) as total FROM sales GROUP BY customer ORDER BY total DESC");
$sales_by_month = $conn->query("SELECT DATE_FORMAT(date, '%b %Y') as month, COUNT(*) as num_sales, SUM(total) as total FROM sales GROUP BY month ORDER BY MIN(date) DESC LIMIT 12");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sales Report</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container-fluid" style="margin-left:250px; padding-top: 30px;">
  <h1 class="mb-4">Sales Report</h1>
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
    <div class="col-md-4">
      <div class="card text-bg-info mb-3">
        <div class="card-body">
          <h5 class="card-title">Unique Customers</h5>
          <p class="card-text fs-4"><?= $num_customers ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <h4>Sales by Customer</h4>
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr><th>Customer</th><th>Number of Sales</th><th>Total Sales</th></tr>
        </thead>
        <tbody>
          <?php while($row = $sales_by_customer->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['customer']) ?></td>
              <td><?= $row['num_sales'] ?></td>
              <td>$<?= number_format($row['total'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="col-md-6">
      <h4>Sales by Month</h4>
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr><th>Month</th><th>Number of Sales</th><th>Total Sales</th></tr>
        </thead>
        <tbody>
          <?php while($row = $sales_by_month->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['month']) ?></td>
              <td><?= $row['num_sales'] ?></td>
              <td>$<?= number_format($row['total'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 