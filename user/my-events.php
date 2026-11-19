<?php require __DIR__.'/../includes/init.php'; need_user(); $title = 'My Events'; $tab = 'my-events';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  q("UPDATE registrations SET status='cancelled' WHERE id=? AND user_id=?", [(int)$_POST['id'], $_SESSION['uid']]); flash('Registration cancelled.', 'info'); go('/user/my-events.php'); }
$rows = q('SELECT r.id rid,r.status,r.created_at,e.id,e.title,e.event_date,e.location FROM registrations r JOIN events e ON e.id=r.event_id WHERE r.user_id=? ORDER BY e.event_date DESC LIMIT 100', [$_SESSION['uid']])->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Event</th><th>Date</th><th>Status</th><th></th></tr></thead><tbody>
<?php if (!$rows) echo '<tr><td colspan="4" class="text-center text-muted py-4">You have not registered for any events yet.</td></tr>';
foreach ($rows as $r): $past = strtotime($r['event_date']) < time(); ?>
<tr><td><a href="/event-details.php?id=<?=$r['id']?>"><?=e($r['title'])?></a><div class="small text-muted"><?=e($r['location'])?></div></td>
<td><?=date('d M Y, g:i A', strtotime($r['event_date']))?></td>
<td><span class="badge bg-<?=$r['status']=='registered'?($past?'secondary':'success'):'danger'?>"><?=$r['status']=='registered'&&$past?'attended/past':$r['status']?></span></td>
<td><?php if ($r['status']=='registered' && !$past): ?><form method="post" data-confirm="Cancel this registration?"><?=csrf()?><input type="hidden" name="id" value="<?=$r['rid']?>"><button class="btn btn-sm btn-outline-danger">Cancel</button></form><?php endif; ?></td></tr>
<?php endforeach; ?></tbody></table></div></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
