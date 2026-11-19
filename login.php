<?php require __DIR__.'/includes/init.php'; $title = 'Login'; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  $u = q('SELECT * FROM users WHERE email=?', [strtolower(trim($_POST['email'] ?? ''))])->fetch();
  if (!$u || !password_verify($_POST['password'] ?? '', $u['password'])) $err = 'Invalid email or password.';
  elseif (!$u['active']) $err = 'Your account has been deactivated.';
  else { session_regenerate_id(true); $_SESSION['uid'] = $u['id']; go('/user/dashboard.php'); } }
include __DIR__.'/includes/header.php'; ?>
<div class="container py-5" style="max-width:440px"><div class="card p-4"><h3>Login</h3>
<?php if ($err) echo '<div class="alert alert-danger py-2">'.e($err).'</div>'; ?>
<form method="post"><?=csrf()?><input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required><button class="btn btn-accent w-100">Login</button></form>
<p class="mt-3 mb-0 small">New here? <a href="/register.php">Create an account</a></p></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
