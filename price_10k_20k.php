<?php
// price_10k_20k.php - Records with price between 10,000 and 20,000
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once __DIR__ . '/../includes/header.php';
require_once "db_connect.php";

try {
    // Check if ANY phones exist first
    $totalCheck = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone")->fetch_assoc()['c'];

    echo "<div style='display:flex;align-items:center;justify-content:space-between;margin-bottom:20px'>
            <div>
              <div class='page-title'>&#128144; Phones Priced 10,000 &ndash; 20,000</div>
              <div class='page-sub'>Mid-range phones in this price bracket</div>
            </div>
          </div>";

    if ($totalCheck == 0) {
        // No phones in DB at all — prompt to add first
        echo "<div style='text-align:center;padding:60px 24px;'>
                <div style='width:72px;height:72px;border-radius:20px;background:#fef9c3;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;'>
                  <svg width='34' height='34' viewBox='0 0 24 24' fill='none' stroke='#d97706' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'><rect x='7' y='2' width='10' height='20' rx='2'/><line x1='7' y1='6' x2='17' y2='6'/><line x1='7' y1='18' x2='17' y2='18'/></svg>
                </div>
                <div style='font-size:20px;font-weight:700;color:#0f172a;margin-bottom:8px;'>No Phone Records Yet</div>
                <div style='font-size:14px;color:#64748b;max-width:360px;margin:0 auto 24px;line-height:1.7;'>
                  Please add mobile phone records first before browsing by price range.
                </div>
                <a href='add_phone.php' class='btn btn-primary' style='text-decoration:none;'>
                  <svg width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='#fff' stroke-width='2.5' stroke-linecap='round' style='vertical-align:middle;margin-right:6px;'><line x1='12' y1='5' x2='12' y2='19'/><line x1='5' y1='12' x2='19' y2='12'/></svg>
                  Add Your First Phone
                </a>
              </div>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM mobile_phone WHERE price > 10000 AND price < 20000 ORDER BY price ASC");
        if (!$stmt) throw new Exception('Prepare failed: ' . $conn->error);
        $stmt->execute();
        $result = $stmt->get_result();
        $cnt = $result->num_rows;

        if ($cnt === 0) {
            echo "<div style='text-align:center;padding:50px 24px;'>
                    <div style='width:64px;height:64px;border-radius:18px;background:#fef9c3;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;'>
                      <svg width='28' height='28' viewBox='0 0 24 24' fill='none' stroke='#d97706' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/></svg>
                    </div>
                    <div style='font-size:17px;font-weight:700;color:#0f172a;margin-bottom:6px;'>No Phones in This Range</div>
                    <div style='font-size:13.5px;color:#64748b;margin-bottom:20px;'>None of your phones are priced between PKR 10,000 and 20,000.</div>
                    <a href='home.php' style='color:#4f46e5;font-size:13.5px;font-weight:600;'>&#8592; Back to Home</a>
                  </div>";
        } else {
            echo "<div class='table-wrap'><table>";
            echo "<thead><tr><th>#</th><th>Phone Name</th><th>Brand</th><th>Price</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>".
                     "<td style='color:#94a3b8;font-size:13px'>".htmlspecialchars($row["id"])."</td>".
                     "<td style='font-weight:600'>".htmlspecialchars($row["mobile_name"])."</td>".
                     "<td>".htmlspecialchars($row["brand"])."</td>".
                     "<td><span class='badge badge-price'>PKR ".number_format($row["price"])."</span></td>".
                     "</tr>";
            }
            echo "</tbody></table></div>";
            echo "<div style='margin-top:14px;color:#64748b;font-size:13px'>$cnt record(s) found. &nbsp;&bull;&nbsp; <a href='home.php' style='color:#4f46e5'>Back to Home</a></div>";
        }
    }

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

require_once __DIR__ . '/../includes/footer.php';
?>
