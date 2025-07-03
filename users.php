<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'retail_db');
$conn->query("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL,
  full_name VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(30),
  mobile VARCHAR(30),
  address VARCHAR(255)
)");
if (isset($_POST['add_user'])) {
  $username = $conn->real_escape_string($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $conn->real_escape_string($_POST['role']);
  $full_name = $conn->real_escape_string($_POST['full_name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $mobile = $conn->real_escape_string($_POST['mobile']);
  $address = $conn->real_escape_string($_POST['address']);
  $conn->query("INSERT INTO users (username, password, role, full_name, email, phone, mobile, address) VALUES ('$username', '$password', '$role', '$full_name', '$email', '$phone', '$mobile', '$address')");
  header('Location: users.php');
  exit;
}
if (isset($_POST['edit_user'])) {
  $id = (int)$_POST['edit_id'];
  $username = $conn->real_escape_string($_POST['edit_username']);
  $role = $conn->real_escape_string($_POST['edit_role']);
  $full_name = $conn->real_escape_string($_POST['edit_full_name']);
  $email = $conn->real_escape_string($_POST['edit_email']);
  $phone = $conn->real_escape_string($_POST['edit_phone']);
  $mobile = $conn->real_escape_string($_POST['edit_mobile']);
  $address = $conn->real_escape_string($_POST['edit_address']);
  $set_password = '';
  if (!empty($_POST['edit_password'])) {
    $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);
    $set_password = ", password='$password'";
  }
  $conn->query("UPDATE users SET username='$username', role='$role', full_name='$full_name', email='$email', phone='$phone', mobile='$mobile', address='$address' $set_password WHERE id=$id");
  header('Location: users.php');
  exit;
}
if (isset($_POST['delete_user'])) {
  $id = (int)$_POST['delete_id'];
  $conn->query("DELETE FROM users WHERE id=$id");
  header('Location: users.php');
  exit;
}
$users = $conn->query("SELECT * FROM users ORDER BY id ASC");
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container-fluid" style="margin-left:250px; padding-top: 30px;">
  <h1>Users</h1>
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while($row = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars(ucfirst($row['role'])) ?></td>
          <td>
            <?php if ($role === 'superadmin' || $role === 'manager'): ?>
              <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row['id'] ?>">Edit</button>
            <?php endif; ?>
            <?php if ($role === 'superadmin'): ?>
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $row['id'] ?>">Delete</button>
            <?php endif; ?>
          </td>
        </tr>
        <div class="modal fade" id="editUserModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $row['id'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="editUserModalLabel<?= $row['id'] ?>">Edit User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="edit_username<?= $row['id'] ?>" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username<?= $row['id'] ?>" name="edit_username" value="<?= htmlspecialchars($row['username']) ?>" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="edit_password<?= $row['id'] ?>" class="form-label">Password (leave blank to keep unchanged)</label>
                        <input type="password" class="form-control" id="edit_password<?= $row['id'] ?>" name="edit_password">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="edit_role<?= $row['id'] ?>" class="form-label">Role</label>
                        <select class="form-select" id="edit_role<?= $row['id'] ?>" name="edit_role" required>
                          <option value="superadmin" <?= $row['role'] == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                          <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                          <option value="manager" <?= $row['role'] == 'manager' ? 'selected' : '' ?>>Manager</option>
                          <option value="staff" <?= $row['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="edit_full_name<?= $row['id'] ?>" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_full_name<?= $row['id'] ?>" name="edit_full_name" value="<?= htmlspecialchars($row['full_name']) ?>" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="edit_email<?= $row['id'] ?>" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email<?= $row['id'] ?>" name="edit_email" value="<?= htmlspecialchars($row['email']) ?>" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="edit_phone<?= $row['id'] ?>" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_phone<?= $row['id'] ?>" name="edit_phone" value="<?= htmlspecialchars($row['phone']) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="edit_mobile<?= $row['id'] ?>" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="edit_mobile<?= $row['id'] ?>" name="edit_mobile" value="<?= htmlspecialchars($row['mobile']) ?>">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="edit_address<?= $row['id'] ?>" class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit_address<?= $row['id'] ?>" name="edit_address" value="<?= htmlspecialchars($row['address']) ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-warning" name="edit_user">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" id="deleteUserModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="deleteUserModalLabel<?= $row['id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel<?= $row['id'] ?>">Delete User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete <strong><?= htmlspecialchars($row['username']) ?></strong>?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger" name="delete_user">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                  <option value="">Select Role</option>
                  <option value="superadmin">Super Admin</option>
                  <option value="admin">Admin</option>
                  <option value="manager">Manager</option>
                  <option value="staff">Staff</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" name="add_user">Add User</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 