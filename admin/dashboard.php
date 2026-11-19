<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Admin'; $tab = 'dashboard';
$s = q("SELECT (SELECT COUNT(*) FROM events) a,(SELECT COUNT(*) FROM events WHERE published=1) b,(SELECT COUNT(*) FROM users) c,
 (SELECT COUNT(*) FROM registrations WHERE status='registered') d,(SELECT COUNT(*) FROM events WHERE event_date>=NOW()) f")->fetch();
$rec = q("SELECT u.name,u.email,e.title,r.created_at,r.status FROM registrations r JOIN users u ON u.id=r.user_id JOIN events e ON e.id=r.event_id ORDER BY r.id DESC LIMIT 5")->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="row g-3"><?php foreach ([['Total events','a'],['Published','b'],['Users','c'],['Registrations','d'],['Upcoming','f']] as $x): ?>
<div class="col-6 col-md"><div class="card p-3 text-center"><div class="stat"><?=$s[$x[1]]?></div><small><?=$x[0]?></small></div></div><?php endforeach; ?></div>
<div class="card p-3 mt-4"><h5>Recent registrations</h5><table class="table mb-0"><?php if (!$rec) echo '<tr><td class="text-muted">None yet.</td></tr>'; foreach ($rec as $r): ?>
<tr><td><?=e($r['name'])?><div class="small text-muted"><?=e($r['email'])?></div></td><td><?=e($r['title'])?></td><td><?=e($r['created_at'])?></td><td><?=e($r['status'])?></td></tr><?php endforeach; ?></table></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
