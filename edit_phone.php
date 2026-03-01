<?php
// edit_phone.php - Edit an existing mobile phone record
// ALL PHP logic must run BEFORE any output (header.php include)
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once "db_connect.php";

$id    = isset($_GET['id'])  ? (int)$_GET['id']  : 0;
$error = '';
$row   = null;

// Handle form submission — redirect happens here, before any HTML output
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $id          = (int)$_POST['id'];
        $mobile_name = trim($_POST["mobile_name"]);
        $brand       = trim($_POST["brand"]);
        $price       = (float)$_POST["price"];

        if (empty($mobile_name) || empty($brand)) {
            throw new Exception("All fields are required.");
        }
        if ($price <= 0) {
            throw new Exception("Price must be a positive number.");
        }

        $stmt = $conn->prepare(
            "UPDATE mobile_phone SET mobile_name=?, brand=?, price=? WHERE id=?");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("ssdi", $mobile_name, $brand, $price, $id);

        if ($stmt->execute()) {
            $_SESSION['flash'] = "Phone record updated successfully.";
            header("Location: retrieve_all.php"); // safe — no output yet
            exit();
        } else {
            throw new Exception("Update failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        // Re-load row so form stays populated after a validation error
        $id = (int)$_POST['id'];
    }
}

// Load existing record (runs on GET, and on POST validation error)
try {
    $stmt = $conn->prepare("SELECT * FROM mobile_phone WHERE id=?");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row && empty($error)) {
        // Record not found — redirect before any output
        header("Location: retrieve_all.php");
        exit();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Only NOW include header.php — no redirects below this line
require_once __DIR__ . '/../includes/header.php';
?>

  <!-- Title -->
  <div style="margin-bottom:22px">
    <div class="page-title">&#9999;&#65039; Edit Phone Record</div>
    <?php if ($row): ?>
    <div class="page-sub">Update details for <strong><?php echo htmlspecialchars($row['mobile_name']); ?></strong></div>
    <?php endif; ?>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($row): ?>
  <div style="max-width:520px">
    <form method="POST" action="edit_phone.php">
      <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

      <div class="form-group">
        <label>Phone Name</label>
        <input type="text" name="mobile_name" class="form-control" required
               value="<?php echo htmlspecialchars($row['mobile_name']); ?>" placeholder="e.g. glaxy j6">
      </div>
      <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" required
               value="<?php echo htmlspecialchars($row['brand']); ?>" placeholder="e.g. samsung">
      </div>
      <div class="form-group">
        <label>Price (PKR)</label>
        <input type="number" name="price" class="form-control" required min="1"
               value="<?php echo htmlspecialchars($row['price']); ?>" placeholder="e.g. 16000">
      </div>

      <div style="display:flex;gap:10px;margin-top:6px">
        <button type="submit" class="btn btn-primary">&#128190; Save Changes</button>
        <a href="retrieve_all.php" class="btn btn-outline" style="text-decoration:none">Cancel</a>
      </div>
    </form>
  </div>
  <?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
