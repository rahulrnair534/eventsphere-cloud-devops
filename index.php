<?php require __DIR__.'/includes/init.php'; $title = 'Home';
$st = q("SELECT (SELECT COUNT(*) FROM events WHERE published=1) ev,(SELECT COUNT(*) FROM registrations WHERE status='registered') rg,(SELECT COUNT(*) FROM users) us")->fetch();
$up = q(EV_SQL." AND e.event_date>=NOW() ORDER BY e.event_date LIMIT 6")->fetchAll();
$pop = q(EV_SQL." AND e.event_date>=NOW() ORDER BY taken DESC LIMIT 3")->fetchAll();
$cats = q("SELECT c.id,c.name,COUNT(e.id) n FROM categories c LEFT JOIN events e ON e.category_id=c.id AND e.published=1 GROUP BY c.id")->fetchAll();
include __DIR__.'/includes/header.php'; ?>
<section class="hero text-center"><div class="container"><h1>Discover Events. Create Memories.</h1>
<p class="lead opacity-75">Find workshops, concerts, conferences and meetups near you.</p>
<form action="/events.php" class="row g-2 justify-content-center mt-4"><div class="col-md-6"><input name="q" class="form-control form-control-lg" placeholder="Search events by name…"></div>
<div class="col-auto"><button class="btn btn-accent btn-lg"><i class="bi bi-search"></i> Search</button></div></form></div></section>
<div class="container"><div class="row text-center g-3 mt-n4 my-4">
<?php foreach ([['Published events',$st['ev']],['Registrations',$st['rg']],['Members',$st['us']]] as $s): ?>
<div class="col-md-4"><div class="card p-3"><div class="stat"><?=$s[1]?></div><div class="text-muted"><?=$s[0]?></div></div></div><?php endforeach; ?></div>
<h3 class="mt-5 mb-3">Categories</h3><div class="d-flex flex-wrap gap-2"><?php foreach ($cats as $c): ?>
<a class="btn btn-outline-secondary rounded-pill" href="/events.php?cat=<?=$c['id']?>"><?=e($c['name'])?> <span class="badge bg-secondary"><?=$c['n']?></span></a><?php endforeach; ?></div>
<h3 class="mt-5 mb-3">Upcoming Events</h3><div class="row g-4">
<?php if (!$up) echo '<div class="text-center text-muted py-5"><i class="bi bi-calendar-x fs-1"></i><p>No upcoming events yet.</p></div>'; foreach ($up as $v) card($v); ?></div>
<h3 class="mt-5 mb-3">Popular Events</h3><div class="row g-4"><?php foreach ($pop as $v) card($v); ?></div>
<h3 class="mt-5 mb-3">Why choose EventSphere?</h3><div class="row g-3"><?php foreach ([['bi-lightning-charge','Fast','Lightweight pages that load quickly.'],['bi-shield-check','Secure','Hashed passwords and CSRF protection.'],['bi-ticket-perforated','Simple','Register or cancel in one click.']] as $w): ?>
<div class="col-md-4"><div class="card p-4 h-100"><i class="bi <?=$w[0]?> fs-2 text-primary"></i><h5 class="mt-2"><?=$w[1]?></h5><p class="mb-0 text-muted"><?=$w[2]?></p></div></div><?php endforeach; ?></div>
<div class="cta text-center p-5 mt-5"><h2>Ready to find your next event?</h2><a href="/events.php" class="btn btn-light btn-lg mt-2">Explore Events</a></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
