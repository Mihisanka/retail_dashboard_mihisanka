<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<aside class="d-flex flex-column flex-shrink-0 p-3 bg-light"
  style="width: 250px; height: 100vh; position: fixed; overflow: visible; z-index: 1100;">
  <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none" style="padding-bottom: 10px;">
    <span class="fs-4">Retail Shop Admin</span>
  </a>
  
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="index.php" class="nav-link <?php if($current == 'index.php') echo 'active'; ?>" aria-current="page">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="customers.php" class="nav-link <?php if($current == 'customers.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-people"></i> Customers
      </a>
    </li>
    <li>
      <a href="add_sale.php" class="nav-link <?php if($current == 'add_sale.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-plus-circle"></i> Add Sale
      </a>
    </li>
    <li>
      <a href="sales.php" class="nav-link <?php if($current == 'sales.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-bar-chart"></i> Sales History
      </a>
    </li>
    <li>
      <a href="report.php" class="nav-link <?php if($current == 'report.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-graph-up"></i> Report
      </a>
    </li>
    <li>
      <a href="users.php" class="nav-link <?php if($current == 'users.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-people-fill"></i> Users
      </a>
    </li>
    <li>
      <a href="admin_profile.php" class="nav-link <?php if($current == 'admin_profile.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-person"></i> Profile
      </a>
    </li>
  </ul>
  <hr>
  <li>
      <a href="logout.php" class="nav-link <?php if($current == 'logout.php') echo 'active'; ?> aria-current="page">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
</aside>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
