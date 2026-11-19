<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Admin Profile'; $tab = 'profile';
$a = q('SELECT * FROM admins WHERE id=?', [$_SESSION['aid']])->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  if (isset($_POST['pw'])) {
    if (!password_verify($_POST['old'], $a['password'])) flash('Current password is wrong.', 'danger');
    elseif (strlen($_POST['new']) < 8 || $_POST['new'] !== $_POST['confirm']) flash('New password must be 8+ chars and match.', 'danger');
    else { q('UPDATE admins SET password=? WHERE id=?', [password_hash($_POST['new'], PASSWORD_DEFAULT), $a['id']]); flash('Password changed.'); }
  } elseif (strlen(trim($_POST['name'])) > 1) { q('UPDATE admins SET name=? WHERE id=?', [trim($_POST['name']), $a['id']]); flash('Profile updated.'); }
  go('/admin/profile.php'); }
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="row g-4"><div class="col-md-6"><div class="card p-4"><h5>Profile</h5><form method="post"><?=csrf()?><input name="name" value="<?=e($a['name'])?>" class="form-control mb-2" required>
<input value="<?=e($a['email'])?>" class="form-control mb-3" disabled><button class="btn btn-accent">Save</button></form></div></div>
<div class="col-md-6"><div class="card p-4"><h5>Change password</h5><form method="post"><?=csrf()?><input type="hidden" name="pw" value="1">
<input type="password" name="old" class="form-control mb-2" placeholder="Current password" required><input type="password" name="new" class="form-control mb-2" placeholder="New password" required>
<input type="password" name="confirm" class="form-control mb-3" placeholder="Confirm" required><button class="btn btn-accent">Update</button></form></div></div></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
