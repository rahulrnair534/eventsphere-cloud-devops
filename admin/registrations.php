<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Registrations'; $tab = 'registrations';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk(); q("UPDATE registrations SET status='cancelled' WHERE id=?", [(int)$_POST['id']]); flash('Registration cancelled.', 'info'); go('/admin/registrations.php'); }
$w = '1'; $p = [];
if ($s = trim($_GET['q'] ?? '')) { $w .= ' AND (u.name LIKE ? OR u.email LIKE ?)'; $p[] = "%$s%"; $p[] = "%$s%"; }
if ($ev = (int)($_GET['event'] ?? 0)) { $w .= ' AND r.event_id=?'; $p[] = $ev; }
$base = "FROM registrations r JOIN users u ON u.id=r.user_id JOIN events e ON e.id=r.event_id WHERE $w";
if (isset($_GET['csv'])) { header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename=registrations.csv');
  $o = fopen('php://output', 'w'); fputcsv($o, ['ID','Name','Email','Phone','Event','Event date','Status','Registered at']);
  $st = q("SELECT r.id,u.name,u.email,u.phone,e.title,e.event_date,r.status,r.created_at $base ORDER BY r.id DESC", $p); while ($r = $st->fetch(PDO::FETCH_NUM)) fputcsv($o, $r); exit; }
$page = max(1, (int)($_GET['page'] ?? 1)); $per = 20; $total = q("SELECT COUNT(*) $base", $p)->fetchColumn();
$rows = q("SELECT r.id,u.name,u.email,u.phone,e.title,r.status,r.created_at $base ORDER BY r.id DESC LIMIT $per OFFSET ".(($page-1)*$per), $p)->fetchAll();
$evs = q('SELECT id,title FROM events ORDER BY title')->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<form class="row g-2 mb-3"><div class="col-md-4"><input name="q" value="<?=e($s)?>" class="form-control" placeholder="Search name or email"></div>
<div class="col-md-4"><select name="event" class="form-select"><option value="">All events</option><?php foreach ($evs as $x): ?><option value="<?=$x['id']?>" <?=$ev==$x['id']?'selected':''?>><?=e($x['title'])?></option><?php endforeach; ?></select></div>
<div class="col-auto"><button class="btn btn-accent">Filter</button></div>
<div class="col-auto"><a class="btn btn-outline-success" href="?<?=e(http_build_query(array_merge($_GET, ['csv'=>1])))?>"><i class="bi bi-download"></i> Export CSV</a></div></form>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Attendee</th><th>Phone</th><th>Event</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>
<?php if (!$rows) echo '<tr><td colspan="6" class="text-center text-muted py-4">No registrations found.</td></tr>'; foreach ($rows as $r): ?>
<tr><td><?=e($r['name'])?><div class="small text-muted"><?=e($r['email'])?></div></td><td><?=e($r['phone'])?></td><td><?=e($r['title'])?></td>
<td><span class="badge bg-<?=$r['status']=='registered'?'success':'danger'?>"><?=e($r['status'])?></span></td><td><?=e($r['created_at'])?></td>
<td><?php if ($r['status']=='registered'): ?><form method="post" data-confirm="Cancel this registration?"><?=csrf()?><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger">Cancel</button></form><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div><div class="mt-3"><?=pager($total, $per, $page)?></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
