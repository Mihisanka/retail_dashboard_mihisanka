<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'retail_db');
$user_id = $_SESSION['user_id'] ?? null;
$user = [
  'username' => '', 'role' => '', 'full_name' => '', 'email' => '', 'phone' => '', 'mobile' => '', 'address' => ''
];
if ($user_id) {
  $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
  }
}
if (isset($_POST['update_profile']) && $user_id) {
  $full_name = $conn->real_escape_string($_POST['full_name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $mobile = $conn->real_escape_string($_POST['mobile']);
  $address = $conn->real_escape_string($_POST['address']);
  $set_password = '';
  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $set_password = ", password='$password'";
  }
  $conn->query("UPDATE users SET full_name='$full_name', email='$email', phone='$phone', mobile='$mobile', address='$address' $set_password WHERE id=$user_id");
  header('Location: admin_profile.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Profile</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<section style="background-color: #eee; margin-left:250px; min-height:100vh;">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
          <i class="bi bi-person" style="padding-right: 10px;"></i>
            <h5 class="my-3"><?= htmlspecialchars($user['full_name'] ?: $user['username']) ?></h5>
            <p class="text-muted mb-1"><?= ucfirst($user['role']) ?></p>
            <p class="text-muted mb-4"><?= htmlspecialchars($user['address']) ?></p>
            <div class="d-flex justify-content-center mb-2">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Full Name</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= htmlspecialchars($user['full_name']) ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Phone</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= htmlspecialchars($user['phone']) ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Mobile</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= htmlspecialchars($user['mobile']) ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Address</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?= htmlspecialchars($user['address']) ?></p>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>
          <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep unchanged)</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 