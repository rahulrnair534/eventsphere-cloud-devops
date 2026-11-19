<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Manage Events'; $tab = 'events';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk(); $id = (int)$_POST['id'];
  if ($_POST['do'] === 'toggle') q('UPDATE events SET published=1-published WHERE id=?', [$id]);
  if ($_POST['do'] === 'delete') q('DELETE FROM events WHERE id=?', [$id]);
  flash('Event updated.'); go('/admin/events.php'); }
$page = max(1, (int)($_GET['page'] ?? 1)); $per = 15; $total = q('SELECT COUNT(*) FROM events')->fetchColumn();
$rows = q("SELECT e.*,(SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id AND r.status='registered') taken FROM events e ORDER BY event_date DESC LIMIT $per OFFSET ".(($page-1)*$per))->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<a href="/admin/event-form.php" class="btn btn-accent mb-3"><i class="bi bi-plus-lg"></i> New event</a>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Title</th><th>Date</th><th>Seats</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?=e($r['title'])?></td><td><?=date('d M Y', strtotime($r['event_date']))?></td><td><?=$r['taken']?>/<?=$r['capacity']?></td>
<td><span class="badge bg-<?=$r['published']?'success':'secondary'?>"><?=$r['published']?'Published':'Draft'?></span></td>
<td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="/admin/event-form.php?id=<?=$r['id']?>">Edit</a>
<?php foreach ([['toggle',$r['published']?'Unpublish':'Publish','secondary',''],['delete','Delete','danger','Delete this event and its registrations?']] as $a): ?>
<form method="post" class="d-inline" <?=$a[3]?'data-confirm="'.$a[3].'"':''?>><?=csrf()?><input type="hidden" name="id" value="<?=$r['id']?>"><input type="hidden" name="do" value="<?=$a[0]?>"><button class="btn btn-sm btn-outline-<?=$a[2]?>"><?=$a[1]?></button></form><?php endforeach; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div><div class="mt-3"><?=pager($total, $per, $page)?></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
