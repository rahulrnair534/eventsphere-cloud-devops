<?php require __DIR__.'/includes/init.php';
$id = (int)($_GET['id'] ?? 0);
$v = q(EV_SQL.' AND e.id=?', [$id])->fetch() ?: go('/events.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk(); need_user();
  $db = db(); $db->beginTransaction();
  $ev = q('SELECT capacity,event_date FROM events WHERE id=? AND published=1 FOR UPDATE', [$id])->fetch();
  $n = q("SELECT COUNT(*) FROM registrations WHERE event_id=? AND status='registered'", [$id])->fetchColumn();
  $ex = q('SELECT id,status FROM registrations WHERE user_id=? AND event_id=?', [$_SESSION['uid'], $id])->fetch();
  if (strtotime($ev['event_date']) < time()) flash('This event has already taken place.', 'warning');
  elseif ($ex && $ex['status'] === 'registered') flash('You are already registered.', 'info');
  elseif ($n >= $ev['capacity']) flash('Sorry, this event is full.', 'danger');
  else { $ex ? q("UPDATE registrations SET status='registered',created_at=NOW() WHERE id=?", [$ex['id']])
             : q('INSERT INTO registrations(user_id,event_id) VALUES(?,?)', [$_SESSION['uid'], $id]);
         flash('🎉 Registration confirmed! See it under My Events.'); }
  $db->commit(); go("/event-details.php?id=$id"); }
$mine = !empty($_SESSION['uid']) ? q('SELECT status FROM registrations WHERE user_id=? AND event_id=?', [$_SESSION['uid'], $id])->fetchColumn() : null;
$left = $v['capacity'] - $v['taken']; $title = $v['title'];
include __DIR__.'/includes/header.php'; ?>
<div class="container py-4"><div class="row g-4"><div class="col-lg-8">
<img src="<?=e($v['image'] ?: 'https://picsum.photos/seed/ev'.$v['id'].'/1000/450')?>" class="img-fluid rounded-4 w-100 mb-3" style="max-height:380px;object-fit:cover" alt="">
<span class="badge badge-cat"><?=e($v['cat'])?></span><h1 class="h2 mt-2"><?=e($v['title'])?></h1><p style="white-space:pre-line"><?=e($v['description'])?></p></div>
<div class="col-lg-4"><div class="card p-4">
<p><i class="bi bi-calendar-event"></i> <?=date('l, d M Y', strtotime($v['event_date']))?><br><i class="bi bi-clock"></i> <?=date('g:i A', strtotime($v['event_date']))?></p>
<p><i class="bi bi-geo-alt"></i> <?=e($v['venue'])?>, <?=e($v['location'])?></p>
<p><i class="bi bi-people"></i> <?=$left>0?"$left of {$v['capacity']} seats available":'Fully booked'?></p>
<?php if ($mine === 'registered'): ?><div class="alert alert-success mb-0">✔ You are registered for this event.</div>
<?php elseif (strtotime($v['event_date']) < time()): ?><div class="alert alert-secondary mb-0">This event has ended.</div>
<?php elseif ($left <= 0): ?><button class="btn btn-secondary w-100" disabled>Sold out</button>
<?php elseif (empty($_SESSION['uid'])): ?><a href="/login.php" class="btn btn-accent w-100">Login to register</a>
<?php else: ?><form method="post"><?=csrf()?><button class="btn btn-accent w-100">Register now</button></form><?php endif; ?>
</div></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
