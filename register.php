<?php require __DIR__.'/includes/init.php'; $title = 'Sign up'; $err = []; $d = ['name'=>'','email'=>'','phone'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  $d = ['name'=>trim($_POST['name']??''), 'email'=>strtolower(trim($_POST['email']??'')), 'phone'=>trim($_POST['phone']??'')];
  $pw = $_POST['password'] ?? '';
  if (strlen($d['name']) < 2) $err[] = 'Please enter your full name.';
  if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $err[] = 'Enter a valid email address.';
  if (!preg_match('/^[0-9+\- ]{7,20}$/', $d['phone'])) $err[] = 'Enter a valid phone number.';
  if (strlen($pw) < 8) $err[] = 'Password must be at least 8 characters.';
  if ($pw !== ($_POST['confirm'] ?? '')) $err[] = 'Passwords do not match.';
  if (!$err && q('SELECT id FROM users WHERE email=?', [$d['email']])->fetch()) $err[] = 'That email is already registered.';
  if (!$err) { q('INSERT INTO users(name,email,phone,password) VALUES(?,?,?,?)', [$d['name'], $d['email'], $d['phone'], password_hash($pw, PASSWORD_DEFAULT)]);
    session_regenerate_id(true); $_SESSION['uid'] = db()->lastInsertId(); flash('Welcome to EventSphere!'); go('/user/dashboard.php'); } }
include __DIR__.'/includes/header.php'; ?>
<div class="container py-5" style="max-width:520px"><div class="card p-4"><h3>Create account</h3>
<?php foreach ($err as $m) echo '<div class="alert alert-danger py-2 mb-2">'.e($m).'</div>'; ?>
<form method="post"><?=csrf()?>
<input name="name" value="<?=e($d['name'])?>" class="form-control mb-2" placeholder="Full name" required>
<input type="email" name="email" value="<?=e($d['email'])?>" class="form-control mb-2" placeholder="Email" required>
<input name="phone" value="<?=e($d['phone'])?>" class="form-control mb-2" placeholder="Phone" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password (min 8)" required>
<input type="password" name="confirm" class="form-control mb-3" placeholder="Confirm password" required>
<button class="btn btn-accent w-100">Sign up</button></form><p class="mt-3 mb-0 small">Have an account? <a href="/login.php">Login</a></p></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
