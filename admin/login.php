<?php require __DIR__.'/../includes/init.php'; $title = 'Admin Login'; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  $a = q('SELECT * FROM admins WHERE email=?', [strtolower(trim($_POST['email'] ?? ''))])->fetch();
  if ($a && password_verify($_POST['password'] ?? '', $a['password'])) { session_regenerate_id(true); $_SESSION['aid'] = $a['id']; go('/admin/dashboard.php'); }
  $err = 'Invalid admin credentials.'; }
include __DIR__.'/../includes/header.php'; ?>
<div class="container py-5" style="max-width:440px"><div class="card p-4"><h3><i class="bi bi-shield-lock"></i> Admin Login</h3>
<?php if ($err) echo '<div class="alert alert-danger py-2">'.e($err).'</div>'; ?>
<form method="post"><?=csrf()?><input type="email" name="email" class="form-control mb-2" placeholder="Admin email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required><button class="btn btn-accent w-100">Login</button></form></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
