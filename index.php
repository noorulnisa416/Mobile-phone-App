<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login — Mobile Phone Repository</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;background:#060b18;}

    /* ── Floating orbs background ── */
    .bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
    .orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.35;animation:drift 12s ease-in-out infinite;}
    .orb1{width:520px;height:520px;background:radial-gradient(circle,#4f46e5,transparent);top:-120px;left:-100px;animation-delay:0s;}
    .orb2{width:400px;height:400px;background:radial-gradient(circle,#06b6d4,transparent);bottom:-80px;right:-80px;animation-delay:-4s;}
    .orb3{width:300px;height:300px;background:radial-gradient(circle,#7c3aed,transparent);top:40%;left:35%;animation-delay:-8s;}
    @keyframes drift{0%,100%{transform:translate(0,0) scale(1);}50%{transform:translate(30px,-20px) scale(1.08);}}

    /* ── Layout ── */
    .page-wrap{
      position:relative;z-index:1;display:flex;width:100%;min-height:100vh;
    }

    /* ── Left branding panel ── */
    .login-left{
      flex:1;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;
      padding:56px 64px;position:relative;overflow:hidden;
    }

    /* decorative grid lines */
    .login-left::before{
      content:'';position:absolute;inset:0;
      background-image:linear-gradient(rgba(99,102,241,0.08) 1px,transparent 1px),
                       linear-gradient(90deg,rgba(99,102,241,0.08) 1px,transparent 1px);
      background-size:48px 48px;
    }

    /* Logo lockup */
    .logo-wrap{display:flex;align-items:center;gap:14px;margin-bottom:48px;position:relative;z-index:1;}
    .logo-icon{
      width:56px;height:56px;border-radius:16px;
      background:linear-gradient(135deg,#4f46e5,#06b6d4);
      display:flex;align-items:center;justify-content:center;
      box-shadow:0 8px 24px rgba(79,70,229,0.45);
      flex-shrink:0;
    }
    .logo-icon svg{width:30px;height:30px;fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
    .logo-text{display:flex;flex-direction:column;line-height:1.15;}
    .logo-text .brand{font-size:18px;font-weight:800;color:#fff;letter-spacing:-.3px;}
    .logo-text .sub-brand{font-size:11.5px;font-weight:400;color:rgba(255,255,255,.5);letter-spacing:.6px;text-transform:uppercase;}

    .tagline{font-size:44px;font-weight:800;color:#fff;line-height:1.15;position:relative;z-index:1;letter-spacing:-1px;}
    .tagline .accent{
      background:linear-gradient(90deg,#818cf8,#22d3ee);
      -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    }
    .tagline-sub{
      color:rgba(255,255,255,.55);font-size:15.5px;margin-top:18px;max-width:400px;
      line-height:1.75;position:relative;z-index:1;font-weight:400;
    }

    /* Feature pills */
    .feature-pills{margin-top:40px;display:flex;flex-direction:column;gap:13px;position:relative;z-index:1;}
    .pill{
      display:inline-flex;align-items:center;gap:12px;
      background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);
      padding:11px 18px;border-radius:12px;max-width:340px;
      backdrop-filter:blur(8px);transition:background .2s;
    }
    .pill:hover{background:rgba(255,255,255,.11);}
    .pill-icon{
      width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;
      font-size:14px;flex-shrink:0;
    }
    .pill-text{font-size:13.5px;color:rgba(255,255,255,.82);font-weight:500;}

    /* Bottom stats strip */
    .stats-strip{
      margin-top:44px;display:flex;gap:28px;position:relative;z-index:1;
      border-top:1px solid rgba(255,255,255,.1);padding-top:28px;
    }
    .stat-item .val{font-size:26px;font-weight:800;color:#fff;}
    .stat-item .lbl{font-size:11.5px;color:rgba(255,255,255,.45);margin-top:3px;text-transform:uppercase;letter-spacing:.5px;}

    /* ── Right form panel ── */
    .login-right{
      width:460px;min-width:340px;display:flex;flex-direction:column;justify-content:center;
      background:rgba(15,23,42,.85);backdrop-filter:blur(24px);
      padding:52px 48px;border-left:1px solid rgba(255,255,255,.07);
    }

    .form-logo{display:flex;align-items:center;gap:10px;margin-bottom:36px;}
    .form-logo .fi{
      width:38px;height:38px;border-radius:10px;
      background:linear-gradient(135deg,#4f46e5,#06b6d4);
      display:flex;align-items:center;justify-content:center;flex-shrink:0;
    }
    .form-logo .fi svg{width:20px;height:20px;fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
    .form-logo .fn{font-size:15px;font-weight:700;color:#e2e8f0;}

    .form-title{font-size:28px;font-weight:800;color:#f1f5f9;letter-spacing:-.5px;}
    .form-hint{color:#64748b;font-size:14px;margin-top:6px;margin-bottom:32px;}

    .fg{margin-bottom:20px;}
    .fg label{
      display:flex;align-items:center;gap:7px;font-weight:600;font-size:12px;
      color:#94a3b8;margin-bottom:8px;letter-spacing:.5px;text-transform:uppercase;
    }
    .fg label svg{width:13px;height:13px;stroke:#64748b;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
    .input-wrap{position:relative;}
    .input-wrap svg{
      position:absolute;left:14px;top:50%;transform:translateY(-50%);
      width:16px;height:16px;stroke:#475569;fill:none;stroke-width:2;
      stroke-linecap:round;stroke-linejoin:round;pointer-events:none;
    }
    .fg input{
      width:100%;padding:12px 14px 12px 42px;
      border:1.5px solid rgba(255,255,255,.1);border-radius:10px;
      font-size:14.5px;color:#f1f5f9;background:rgba(255,255,255,.06);
      outline:none;transition:border-color .18s,box-shadow .18s,background .18s;
      font-family:inherit;
    }
    .fg input::placeholder{color:#475569;}
    .fg input:focus{
      border-color:#4f46e5;background:rgba(79,70,229,.08);
      box-shadow:0 0 0 3px rgba(79,70,229,.2);
    }

    .btn-login{
      width:100%;padding:14px;border:none;border-radius:10px;margin-top:8px;
      background:linear-gradient(135deg,#4f46e5 0%,#3730a3 50%,#1d4ed8 100%);
      color:#fff;font-size:15px;font-weight:700;cursor:pointer;
      letter-spacing:.2px;transition:all .2s;position:relative;overflow:hidden;
      font-family:inherit;
    }
    .btn-login::before{
      content:'';position:absolute;inset:0;
      background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);
      opacity:0;transition:opacity .2s;
    }
    .btn-login:hover::before{opacity:1;}
    .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(79,70,229,.5);}
    .btn-login:active{transform:translateY(0);}

    .error-box{
      background:rgba(239,68,68,.12);color:#fca5a5;border-radius:10px;
      padding:11px 14px;font-size:13.5px;margin-top:16px;
      border:1px solid rgba(239,68,68,.3);display:flex;align-items:center;gap:8px;
    }
    .error-box::before{content:'⚠';font-size:14px;}

    .divider{
      display:flex;align-items:center;gap:12px;margin:22px 0 18px;
    }
    .divider .line{flex:1;height:1px;background:rgba(255,255,255,.08);}
    .divider span{font-size:12px;color:#475569;}

    .creds-card{
      background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
      border-radius:10px;padding:13px 16px;display:flex;align-items:center;gap:12px;
    }
    .creds-card svg{width:18px;height:18px;stroke:#64748b;fill:none;stroke-width:2;flex-shrink:0;stroke-linecap:round;stroke-linejoin:round;}
    .creds-card .cc-text{font-size:12.5px;color:#64748b;}
    .creds-card .cc-text strong{color:#94a3b8;font-weight:600;}

    @media(max-width:900px){
      .login-left{padding:40px 36px;}
      .tagline{font-size:34px;}
    }
    @media(max-width:720px){
      .login-left{display:none;}
      .login-right{width:100%;border-left:none;padding:40px 28px;}
    }
  </style>
</head>
<body>

<div class="bg-orbs">
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
</div>

<div class="page-wrap">

  <!-- ═══ Left branding panel ═══ -->
  <div class="login-left">

    <div class="logo-wrap">
      <div class="logo-icon">
        <!-- Phone + database SVG icon -->
        <svg viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="7" y1="6" x2="17" y2="6"/><line x1="7" y1="18" x2="17" y2="18"/><circle cx="12" cy="21" r=".5" fill="#fff"/></svg>
      </div>
      <div class="logo-text">
        <span class="brand">Mobile Phone Repository</span>
      </div>
    </div>

    <div class="tagline">
      <span class="accent">Mobile Phone</span><br>
      Repository
    </div>
    <p class="tagline-sub">
      Store, search, and manage your entire mobile phone inventory from a single, secure dashboard with real-time filtering.
    </p>

    <div class="feature-pills">
      <div class="pill">
        <div class="pill-icon" style="background:rgba(79,70,229,.25);">📥</div>
        <span class="pill-text">Insert &amp; manage phone records</span>
      </div>
      <div class="pill">
        <div class="pill-icon" style="background:rgba(6,182,212,.25);">🔍</div>
        <span class="pill-text">Filter by price range instantly</span>
      </div>
      <div class="pill">
        <div class="pill-icon" style="background:rgba(124,58,237,.25);">✏️</div>
        <span class="pill-text">Edit &amp; delete with full control</span>
      </div>
      <div class="pill">
        <div class="pill-icon" style="background:rgba(5,150,105,.25);">🔒</div>
        <span class="pill-text">Secure session-based login</span>
      </div>
    </div>

    <div class="stats-strip">
      <div class="stat-item"><div class="val">CRUD</div><div class="lbl">Operations</div></div>
      <div class="stat-item"><div class="val">MySQL</div><div class="lbl">Database</div></div>
      <div class="stat-item"><div class="val">PHP</div><div class="lbl">Backend</div></div>
    </div>
  </div>

  <!-- ═══ Right login form ═══ -->
  <div class="login-right">

    <div class="form-logo">
      <div class="fi">
        <svg viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="7" y1="6" x2="17" y2="6"/><line x1="7" y1="18" x2="17" y2="18"/></svg>
      </div>
      <span class="fn">Mobile Phone Repository</span>
    </div>

    <div class="form-title">Welcome back</div>
    <p class="form-hint">Sign in to access your phone repository</p>

    <form method="POST" action="login.php">

      <div class="fg">
        <label>
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Username
        </label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input type="text" name="username" required placeholder="Enter your username" autocomplete="username">
        </div>
      </div>

      <div class="fg">
        <label>
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Password
        </label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
        </div>
      </div>

      <button class="btn-login" type="submit">Sign In &nbsp;→</button>

      <?php if(isset($error)) echo "<div class='error-box'>".htmlspecialchars($error)."</div>"; ?>
    </form>

  </div>
</div>

</body>
</html>
