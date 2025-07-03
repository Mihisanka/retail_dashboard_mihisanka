<?php
$conn = new mysqli('localhost', 'root', '', 'retail_db');
if (isset($_POST['add_sale'])) {
  $date = $conn->real_escape_string($_POST['sale_date']);
  $customer = $conn->real_escape_string($_POST['customer']);
  $total = (float)$_POST['total'];
  $conn->query("INSERT INTO sales (date, customer, total) VALUES ('$date', '$customer', $total)");
  header('Location: sales.php');
  exit;
}
$customers = $conn->query("SELECT name FROM customers ORDER BY name ASC");
$customer_list = [];
while ($c = $customers->fetch_assoc()) {
  $customer_list[] = $c['name'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Sale</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="margin-left:250px;">
  <div style="width:100%; max-width:600px;">
    <h1 class="mb-4">Add Sale</h1>
    <form method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="sale_date" class="form-label">Date</label>
        <input type="date" class="form-control" id="sale_date" name="sale_date" required>
      </div>
      <div class="mb-3">
        <label for="customer" class="form-label">Customer</label>
        <select class="form-select" id="customer" name="customer" required>
          <option value="">Select Customer</option>
          <option value="Walking Customer">Walking Customer</option>
          <?php foreach($customer_list as $name): ?>
            <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="total" class="form-label">Total</label>
        <input type="number" step="0.01" class="form-control" id="total" name="total" required>
      </div>
      <button type="submit" class="btn btn-success" name="add_sale">Add Sale</button>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 