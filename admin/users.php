<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Users'; $tab = 'users';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk(); q('UPDATE users SET active=1-active WHERE id=?', [(int)$_POST['id']]); flash('User status changed.'); go('/admin/users.php'); }
$s = trim($_GET['q'] ?? ''); $page = max(1, (int)($_GET['page'] ?? 1)); $per = 20; $p = $s ? ["%$s%", "%$s%"] : []; $w = $s ? 'WHERE name LIKE ? OR email LIKE ?' : '';
$total = q("SELECT COUNT(*) FROM users $w", $p)->fetchColumn();
$rows = q("SELECT u.*,(SELECT COUNT(*) FROM registrations r WHERE r.user_id=u.id AND r.status='registered') regs FROM users u $w ORDER BY id DESC LIMIT $per OFFSET ".(($page-1)*$per), $p)->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<form class="d-flex gap-2 mb-3"><input name="q" value="<?=e($s)?>" class="form-control" placeholder="Search users"><button class="btn btn-accent">Search</button></form>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Events</th><th>Joined</th><th>Status</th></tr></thead><tbody>
<?php if (!$rows) echo '<tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>'; foreach ($rows as $u): ?>
<tr><td><?=e($u['name'])?></td><td><?=e($u['email'])?></td><td><?=e($u['phone'])?></td><td><?=$u['regs']?></td><td><?=date('d M Y', strtotime($u['created_at']))?></td>
<td><form method="post"><?=csrf()?><input type="hidden" name="id" value="<?=$u['id']?>"><button class="btn btn-sm btn-<?=$u['active']?'success':'secondary'?>"><?=$u['active']?'Active':'Deactivated'?></button></form></td></tr><?php endforeach; ?>
</tbody></table></div></div><div class="mt-3"><?=pager($total, $per, $page)?></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
