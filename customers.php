<?php
$conn = new mysqli('localhost', 'root', '', 'retail_db');
if (isset($_POST['add_customer'])) {
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $conn->query("INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')");
  header('Location: customers.php');
  exit;
}
if (isset($_POST['edit_customer'])) {
  $id = (int)$_POST['customer_id'];
  $name = $conn->real_escape_string($_POST['edit_name']);
  $email = $conn->real_escape_string($_POST['edit_email']);
  $phone = $conn->real_escape_string($_POST['edit_phone']);
  $conn->query("UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id=$id");
  header('Location: customers.php');
  exit;
}
if (isset($_POST['delete_customer'])) {
  $id = (int)$_POST['delete_id'];
  $conn->query("DELETE FROM customers WHERE id=$id");
  header('Location: customers.php');
  exit;
}
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM customers WHERE name LIKE '%$search%' ORDER BY name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Customer List</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container-fluid" style="margin-left:250px; padding-top: 30px;">
  <h1>Customers</h1>
  <form method="GET" class="mb-3 d-flex" style="max-width:400px;">
    <input type="text" name="search" class="form-control me-2" style="width: 100%;" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
    <input class="btn btn-primary" type="submit" value="Submit">
  </form>
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td>
            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editCustomerModal<?= $row['id'] ?>">Edit</button>
            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal<?= $row['id'] ?>">Delete</button>
          </td>
        </tr>
        <div class="modal fade" id="editCustomerModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editCustomerModalLabel<?= $row['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="customer_id" value="<?= $row['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="editCustomerModalLabel<?= $row['id'] ?>">Edit Customer</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="edit_name<?= $row['id'] ?>" class="form-label">Name</label>
                    <input type="text" class="form-control" id="edit_name<?= $row['id'] ?>" name="edit_name" value="<?= htmlspecialchars($row['name']) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit_email<?= $row['id'] ?>" class="form-label">Email</label>
                    <input type="email" class="form-control" id="edit_email<?= $row['id'] ?>" name="edit_email" value="<?= htmlspecialchars($row['email']) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit_phone<?= $row['id'] ?>" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="edit_phone<?= $row['id'] ?>" name="edit_phone" value="<?= htmlspecialchars($row['phone']) ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-warning" name="edit_customer">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" id="deleteCustomerModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="deleteCustomerModalLabel<?= $row['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteCustomerModalLabel<?= $row['id'] ?>">Delete Customer</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete <strong><?= htmlspecialchars($row['name']) ?></strong>?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger" name="delete_customer">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="add_customer">Add Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
