<?php
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once __DIR__ . '/../includes/header.php';

$flashError = $_SESSION['flash_error'] ?? ''; unset($_SESSION['flash_error']);
?>

  <div style="margin-bottom:22px">
    <div class="page-title">&#10133; Add New Mobile Phone</div>
    <div class="page-sub">Fill in the details below to add a new phone record</div>
  </div>

  <?php if ($flashError): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($flashError); ?></div>
  <?php endif; ?>

  <div style="max-width:520px">
    <form method="POST" action="insert_phone.php">
      <div class="form-group">
        <label>Phone Name</label>
        <input type="text" name="mobile_name" class="form-control" required placeholder="e.g. glaxy j6">
      </div>
      <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" required placeholder="e.g. samsung">
      </div>
      <div class="form-group">
        <label>Price (PKR)</label>
        <input type="number" name="price" class="form-control" required min="1" placeholder="e.g. 16000">
      </div>
      <div style="display:flex;gap:10px;margin-top:6px">
        <button class="btn btn-primary" type="submit">&#128190; Save Phone</button>
        <a href="home.php" class="btn btn-outline" style="text-decoration:none">Cancel</a>
      </div>
    </form>
  </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
