<?php require __DIR__.'/../includes/init.php'; need_user(); $title = 'Dashboard'; $tab = 'dashboard';
$u = q('SELECT * FROM users WHERE id=?', [$_SESSION['uid']])->fetch() ?: go('/login.php');
$s = q("SELECT COUNT(*) t, SUM(e.event_date>=NOW()) up, SUM(e.event_date<NOW()) past FROM registrations r JOIN events e ON e.id=r.event_id WHERE r.user_id=? AND r.status='registered'", [$u['id']])->fetch();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<h3>Welcome, <?=e($u['name'])?> 👋</h3><p class="text-muted"><?=e($u['email'])?> · <?=e($u['phone'])?></p>
<div class="row g-3"><?php foreach ([['Registered events',$s['t']],['Upcoming',$s['up']],['Past',$s['past']]] as $x): ?>
<div class="col-md-4"><div class="card p-3 text-center"><div class="stat"><?=(int)$x[1]?></div><?=$x[0]?></div></div><?php endforeach; ?></div>
<a href="/events.php" class="btn btn-accent mt-4">Find more events</a></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
