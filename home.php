<?php
session_start();
if (!isset($_SESSION["user"])) { header("Location: index.php"); exit(); }
require_once __DIR__ . '/../includes/header.php';
require_once "db_connect.php";

// ── Stats ──
$total = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone")->fetch_assoc()['c'];
$range = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone WHERE price > 10000 AND price < 20000")->fetch_assoc()['c'];
$above = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone WHERE price > 20000")->fetch_assoc()['c'];
$below = $conn->query("SELECT COUNT(*) AS c FROM mobile_phone WHERE price <= 10000")->fetch_assoc()['c'];
$avg   = $conn->query("SELECT ROUND(AVG(price),0) AS a FROM mobile_phone")->fetch_assoc()['a'];

// ── 5 most recent phones ──
// (recently added section removed)

// Greeting by time
$hour = (int)date('H');
$greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
?>

<style>
/* ── Hero banner ── */
.hero{
  background:linear-gradient(135deg,#1e1b4b 0%,#312e81 45%,#1d4ed8 80%,#0891b2 120%);
  border-radius:20px;padding:36px 40px;margin-bottom:28px;
  display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;
  position:relative;overflow:hidden;
}
.hero::before{
  content:'';position:absolute;width:380px;height:380px;border-radius:50%;
  background:rgba(99,102,241,.18);top:-120px;right:-80px;
}
.hero::after{
  content:'';position:absolute;width:220px;height:220px;border-radius:50%;
  background:rgba(6,182,212,.12);bottom:-60px;right:300px;
}
.hero-left{position:relative;z-index:1;}
.hero-greet{font-size:13px;font-weight:600;color:rgba(255,255,255,.6);letter-spacing:.8px;text-transform:uppercase;margin-bottom:8px;}
.hero-name{font-size:32px;font-weight:800;color:#fff;letter-spacing:-.5px;line-height:1.15;}
.hero-name span{
  background:linear-gradient(90deg,#a5b4fc,#67e8f9);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-sub{color:rgba(255,255,255,.55);font-size:14px;margin-top:8px;}
.hero-date{font-size:12.5px;color:rgba(255,255,255,.4);margin-top:6px;}
.hero-right{position:relative;z-index:1;display:flex;flex-direction:column;align-items:flex-end;gap:10px;}
.hero-phone-art{
  width:72px;height:72px;border-radius:18px;
  background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);
  display:flex;align-items:center;justify-content:center;
  backdrop-filter:blur(8px);margin-bottom:4px;
}
.hero-phone-art svg{width:38px;height:38px;stroke:#fff;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;}
.hero-cta{
  display:inline-flex;align-items:center;gap:8px;
  background:#fff;color:#3730a3;font-weight:700;font-size:13.5px;
  padding:11px 22px;border-radius:10px;text-decoration:none;
  transition:all .18s;box-shadow:0 4px 14px rgba(0,0,0,.2);
}
.hero-cta:hover{background:#eef2ff;transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.25);}
.hero-cta svg{width:16px;height:16px;stroke:#3730a3;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;}

/* ── Stat cards ── */
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-bottom:28px;}
.stat-card{
  border-radius:16px;padding:24px 22px;color:#fff;position:relative;overflow:hidden;
  transition:transform .18s,box-shadow .18s;cursor:default;
}
.stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,.18);}
.stat-card::after{
  content:'';position:absolute;width:130px;height:130px;border-radius:50%;
  background:rgba(255,255,255,.08);bottom:-40px;right:-30px;
}
.sc-icon{
  width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.18);
  display:flex;align-items:center;justify-content:center;margin-bottom:16px;
}
.sc-icon svg{width:22px;height:22px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.sc-val{font-size:34px;font-weight:800;letter-spacing:-1px;line-height:1;}
.sc-lbl{font-size:12.5px;opacity:.75;margin-top:6px;font-weight:500;letter-spacing:.2px;}
.sc1{background:linear-gradient(135deg,#4f46e5,#3730a3);}
.sc2{background:linear-gradient(135deg,#0891b2,#0e7490);}
.sc3{background:linear-gradient(135deg,#7c3aed,#6d28d9);}
.sc4{background:linear-gradient(135deg,#059669,#047857);}

/* ── Actions section ── */
.home-grid{display:grid;grid-template-columns:1fr;gap:20px;}

/* ── Action cards ── */
.section-title{font-size:15px;font-weight:700;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.section-title svg{width:16px;height:16px;stroke:#4f46e5;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.action-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:10px;}
.action-card{
  display:flex;align-items:center;gap:14px;
  background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;
  padding:16px 18px;text-decoration:none;
  transition:all .18s;box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.action-card:hover{border-color:var(--ac);box-shadow:0 4px 18px rgba(0,0,0,.09);transform:translateX(4px);}
.ac-ico{
  width:42px;height:42px;border-radius:11px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
}
.ac-ico svg{width:20px;height:20px;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.ac-text .ac-name{font-size:14px;font-weight:700;}
.ac-text .ac-desc{font-size:12px;color:#64748b;margin-top:2px;}
.ac-arrow{margin-left:auto;flex-shrink:0;}
.ac-arrow svg{width:16px;height:16px;stroke:#cbd5e1;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.action-card:hover .ac-arrow svg{stroke:#94a3b8;}

/* ── Recent records mini-table ── */
.recent-box{
  background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;
  overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.recent-header{
  padding:16px 20px;border-bottom:1px solid #f1f5f9;
  display:flex;align-items:center;justify-content:space-between;
}
.recent-header .rh-title{font-size:15px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;}
.recent-header .rh-title svg{width:15px;height:15px;stroke:#4f46e5;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.recent-header a{font-size:12.5px;color:#4f46e5;text-decoration:none;font-weight:600;}
.recent-header a:hover{text-decoration:underline;}
.rt{width:100%;border-collapse:collapse;font-size:13.5px;}
.rt th{padding:10px 16px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid #f1f5f9;text-align:left;background:#fafafa;}
.rt td{padding:11px 16px;border-bottom:1px solid #f8fafc;color:#1e293b;vertical-align:middle;}
.rt tr:last-child td{border-bottom:none;}
.rt tr:hover td{background:#fafbff;}
.rt .brand-pill{
  font-size:11.5px;font-weight:600;padding:3px 9px;border-radius:20px;
  background:#e0e7ff;color:#3730a3;
}
.rt .price-tag{font-weight:700;color:#059669;font-size:13px;}
.empty-state{
  padding:36px;text-align:center;color:#94a3b8;font-size:13.5px;
}
.empty-state svg{width:32px;height:32px;stroke:#cbd5e1;fill:none;stroke-width:1.5;margin-bottom:10px;display:block;margin-inline:auto;stroke-linecap:round;stroke-linejoin:round;}
</style>

<!-- ═══ Hero Banner ═══ -->
<div class="hero">
  <div class="hero-left">
    <div class="hero-greet"><?php echo $greet; ?></div>
    <div class="hero-name"><?php echo htmlspecialchars($_SESSION["user"]); ?> <span>&#128075;</span></div>
    <div class="hero-sub">Here&rsquo;s your Mobile Phone Repository overview</div>
    <div class="hero-date"><?php echo date('l, d F Y'); ?></div>
  </div>
  <div class="hero-right">
    <div class="hero-phone-art">
      <svg viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="7" y1="6" x2="17" y2="6"/><line x1="7" y1="18" x2="17" y2="18"/><circle cx="12" cy="21" r=".5" fill="#fff"/></svg>
    </div>
    <a href="add_phone.php" class="hero-cta">
      <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add New Phone
    </a>
  </div>
</div>

<!-- ═══ Stat Cards ═══ -->
<div class="stat-grid">
  <div class="stat-card sc1">
    <div class="sc-icon"><svg viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="7" y1="6" x2="17" y2="6"/><line x1="7" y1="18" x2="17" y2="18"/></svg></div>
    <div class="sc-val"><?php echo $total; ?></div>
    <div class="sc-lbl">Total Phones</div>
  </div>
  <div class="stat-card sc2">
    <div class="sc-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
    <div class="sc-val"><?php echo $range; ?></div>
    <div class="sc-lbl">Mid-Range (10k&ndash;20k)</div>
  </div>
  <div class="stat-card sc3">
    <div class="sc-icon"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
    <div class="sc-val"><?php echo $above; ?></div>
    <div class="sc-lbl">Premium (&gt; 20k)</div>
  </div>
  <div class="stat-card sc4">
    <div class="sc-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
    <div class="sc-val">PKR&nbsp;<?php echo number_format($avg ?? 0); ?></div>
    <div class="sc-lbl">Average Price</div>
  </div>
</div>

<!-- ═══ Lower Section: Actions + Recent ═══ -->
<div class="home-grid">

  <!-- Quick Actions -->
  <div>
    <div class="section-title">
      <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
      Quick Actions
    </div>
    <div class="action-list">

      <a href="add_phone.php" class="action-card" style="--ac:#4f46e5">
        <div class="ac-ico" style="background:#eef2ff;">
          <svg style="stroke:#4f46e5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div class="ac-text">
          <div class="ac-name" style="color:#3730a3">Add New Phone</div>
          <div class="ac-desc">Insert a new phone record into the database</div>
        </div>
        <div class="ac-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
      </a>

      <a href="retrieve_all.php" class="action-card" style="--ac:#0891b2">
        <div class="ac-ico" style="background:#e0f2fe;">
          <svg style="stroke:#0891b2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <div class="ac-text">
          <div class="ac-name" style="color:#0e7490">All Phone Records</div>
          <div class="ac-desc">View, edit and delete all stored phones</div>
        </div>
        <div class="ac-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
      </a>

      <a href="price_10k_20k.php" class="action-card" style="--ac:#d97706">
        <div class="ac-ico" style="background:#fef9c3;">
          <svg style="stroke:#d97706" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="ac-text">
          <div class="ac-name" style="color:#b45309">Price 10,000 &ndash; 20,000</div>
          <div class="ac-desc">Browse mid-range phone records</div>
        </div>
        <div class="ac-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
      </a>

      <a href="price_above_20k.php" class="action-card" style="--ac:#7c3aed">
        <div class="ac-ico" style="background:#f5f3ff;">
          <svg style="stroke:#7c3aed" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="ac-text">
          <div class="ac-name" style="color:#6d28d9">Price Above 20,000</div>
          <div class="ac-desc">Browse premium phone records</div>
        </div>
        <div class="ac-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
      </a>

    </div>
  </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
