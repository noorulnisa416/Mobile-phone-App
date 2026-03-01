<?php
// retrieve_all.php - Fetch and display all mobile phone records
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once __DIR__ . '/../includes/header.php';
require_once "db_connect.php";

$flash      = $_SESSION['flash']       ?? ''; unset($_SESSION['flash']);
$flashError = $_SESSION['flash_error'] ?? ''; unset($_SESSION['flash_error']);
?>

  <!-- Title row -->
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <div>
      <div class="page-title">All Mobile Phones</div>
      <div class="page-sub">Complete list of phone records in the repository</div>
    </div>
    <a href="add_phone.php" class="btn btn-primary" style="text-decoration:none">➕ Add Phone</a>
  </div>

  <?php if ($flash): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($flash); ?></div>
  <?php endif; ?>
  <?php if ($flashError): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($flashError); ?></div>
  <?php endif; ?>

<?php
try {
    // Check total records first
    $totalCheck = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone")->fetch_assoc()['c'];

    if ($totalCheck == 0):
?>
  <div style="text-align:center;padding:60px 24px;">
    <div style="width:72px;height:72px;border-radius:20px;background:#eef2ff;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="7" y1="6" x2="17" y2="6"/><line x1="7" y1="18" x2="17" y2="18"/></svg>
    </div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;margin-bottom:8px;">No Phone Records Yet</div>
    <div style="font-size:14px;color:#64748b;max-width:360px;margin:0 auto 24px;line-height:1.7;">
      You haven't added any mobile phones to the repository.<br>Start by inserting your first phone record.
    </div>
    <a href="add_phone.php" class="btn btn-primary" style="text-decoration:none;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" style="vertical-align:middle;margin-right:6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Your First Phone
    </a>
  </div>
<?php
    else:
        $result = $conn->query("SELECT * FROM mobile_phone ORDER BY id ASC");
        if ($result === false) throw new Exception("Query failed: " . $conn->error);
?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Phone Name</th>
          <th>Brand</th>
          <th>Price</th>
          <th style="text-align:center">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td style="color:#94a3b8;font-size:13px"><?php echo $row["id"]; ?></td>
          <td style="font-weight:600"><?php echo htmlspecialchars($row["mobile_name"]); ?></td>
          <td><?php echo htmlspecialchars($row["brand"]); ?></td>
          <td><span class="badge badge-price">PKR <?php echo number_format($row["price"]); ?></span></td>
          <td style="text-align:center;white-space:nowrap">
            <a href="edit_phone.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" style="text-decoration:none;margin-right:4px">✏️ Edit</a>
            <a href="delete_phone.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" style="text-decoration:none"
               onclick="return confirm('Delete <?php echo htmlspecialchars(addslashes($row['mobile_name'])); ?>? This cannot be undone.')">🗑️ Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px;color:#64748b;font-size:13px;">
    <?php echo $totalCheck; ?> record(s) found.
    &nbsp;&bull;&nbsp; <a href="home.php" style="color:#4f46e5">Back to Home</a>
  </div>
<?php
    endif;
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

require_once __DIR__ . '/../includes/footer.php';
?>
