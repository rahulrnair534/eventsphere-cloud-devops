<?php require __DIR__.'/../includes/init.php'; need_user(); $title = 'Profile'; $tab = 'profile';
$u = q('SELECT * FROM users WHERE id=?', [$_SESSION['uid']])->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  if (isset($_POST['pw'])) {
    if (!password_verify($_POST['old'], $u['password'])) flash('Current password is wrong.', 'danger');
    elseif (strlen($_POST['new']) < 8 || $_POST['new'] !== $_POST['confirm']) flash('New password must be 8+ chars and match confirmation.', 'danger');
    else { q('UPDATE users SET password=? WHERE id=?', [password_hash($_POST['new'], PASSWORD_DEFAULT), $u['id']]); flash('Password changed.'); }
  } else { $n = trim($_POST['name']); $p = trim($_POST['phone']);
    if (strlen($n) < 2 || !preg_match('/^[0-9+\- ]{7,20}$/', $p)) flash('Please enter a valid name and phone.', 'danger');
    else { q('UPDATE users SET name=?,phone=? WHERE id=?', [$n, $p, $u['id']]); flash('Profile updated.'); } }
  go('/user/profile.php'); }
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="row g-4"><div class="col-md-6"><div class="card p-4"><h5>Edit profile</h5><form method="post"><?=csrf()?>
<input name="name" value="<?=e($u['name'])?>" class="form-control mb-2" required><input value="<?=e($u['email'])?>" class="form-control mb-2" disabled>
<input name="phone" value="<?=e($u['phone'])?>" class="form-control mb-3" required><button class="btn btn-accent">Save</button></form></div></div>
<div class="col-md-6"><div class="card p-4"><h5>Change password</h5><form method="post"><?=csrf()?><input type="hidden" name="pw" value="1">
<input type="password" name="old" class="form-control mb-2" placeholder="Current password" required><input type="password" name="new" class="form-control mb-2" placeholder="New password" required>
<input type="password" name="confirm" class="form-control mb-3" placeholder="Confirm new password" required><button class="btn btn-accent">Update</button></form></div></div></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
