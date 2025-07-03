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
if (isset($_POST['edit_sale'])) {
  $id = (int)$_POST['sale_id'];
  $date = $conn->real_escape_string($_POST['edit_sale_date']);
  $customer = $conn->real_escape_string($_POST['edit_customer']);
  $total = (float)$_POST['edit_total'];
  $conn->query("UPDATE sales SET date='$date', customer='$customer', total=$total WHERE id=$id");
  header('Location: sales.php');
  exit;
}
if (isset($_POST['delete_sale'])) {
  $id = (int)$_POST['delete_id'];
  $conn->query("DELETE FROM sales WHERE id=$id");
  header('Location: sales.php');
  exit;
}
$customers = $conn->query("SELECT name FROM customers ORDER BY name ASC");
$customer_list = [];
while ($c = $customers->fetch_assoc()) {
  $customer_list[] = $c['name'];
}
$sales = $conn->query("SELECT * FROM sales ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sales History</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container-fluid" style="margin-left:250px; padding-top: 30px;">
  <h1>Sales History</h1>
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSaleModal">Add Sale</button>
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr><th>Date</th><th>Customer</th><th>Total</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while($s = $sales->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($s['date']) ?></td>
          <td><?= htmlspecialchars($s['customer']) ?></td>
          <td>$<?= number_format($s['total'], 2) ?></td>
          <td>
            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editSaleModal<?= $s['id'] ?>">Edit</button>
            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSaleModal<?= $s['id'] ?>">Delete</button>
          </td>
        </tr>
        <div class="modal fade" id="editSaleModal<?= $s['id'] ?>" tabindex="-1" aria-labelledby="editSaleModalLabel<?= $s['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="sale_id" value="<?= $s['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="editSaleModalLabel<?= $s['id'] ?>">Edit Sale</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="edit_sale_date<?= $s['id'] ?>" class="form-label">Date</label>
                    <input type="date" class="form-control" id="edit_sale_date<?= $s['id'] ?>" name="edit_sale_date" value="<?= htmlspecialchars($s['date']) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit_customer<?= $s['id'] ?>" class="form-label">Customer</label>
                    <select class="form-select" id="edit_customer<?= $s['id'] ?>" name="edit_customer" required>
                      <option value="">Select Customer</option>
                      <option value="Walking Customer">Walking Customer</option>
                      <?php foreach($customer_list as $name): ?>
                        <option value="<?= htmlspecialchars($name) ?>" <?= $s['customer'] == $name ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="edit_total<?= $s['id'] ?>" class="form-label">Total</label>
                    <input type="number" step="0.01" class="form-control" id="edit_total<?= $s['id'] ?>" name="edit_total" value="<?= htmlspecialchars($s['total']) ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-warning" name="edit_sale">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" id="deleteSaleModal<?= $s['id'] ?>" tabindex="-1" aria-labelledby="deleteSaleModalLabel<?= $s['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="delete_id" value="<?= $s['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteSaleModalLabel<?= $s['id'] ?>">Delete Sale</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete this sale for <strong><?= htmlspecialchars($s['customer']) ?></strong> on <strong><?= htmlspecialchars($s['date']) ?></strong>?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger" name="delete_sale">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="addSaleModal" tabindex="-1" aria-labelledby="addSaleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addSaleModalLabel">Add Sale</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="add_sale">Add Sale</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
