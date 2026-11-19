<?php require __DIR__.'/includes/init.php'; $title = 'Events';
$w = []; $p = [];
if ($s = trim($_GET['q'] ?? '')) { $w[] = 'e.title LIKE ?'; $p[] = "%$s%"; }
if ($c = (int)($_GET['cat'] ?? 0)) { $w[] = 'e.category_id=?'; $p[] = $c; }
if ($l = trim($_GET['loc'] ?? '')) { $w[] = 'e.location LIKE ?'; $p[] = "%$l%"; }
if (!empty($_GET['upcoming'])) $w[] = 'e.event_date>=NOW()';
$ord = ($_GET['sort'] ?? '') === 'desc' ? 'DESC' : 'ASC'; $page = max(1, (int)($_GET['page'] ?? 1)); $per = 9;
$wh = $w ? ' AND '.implode(' AND ', $w) : '';
$total = q("SELECT COUNT(*) FROM events e WHERE e.published=1$wh", $p)->fetchColumn();
$rows = q(EV_SQL."$wh ORDER BY e.event_date $ord LIMIT $per OFFSET ".(($page-1)*$per), $p)->fetchAll();
$cats = q('SELECT * FROM categories ORDER BY name')->fetchAll();
include __DIR__.'/includes/header.php'; ?>
<div class="container py-4"><h2 class="mb-3">Browse Events</h2>
<form class="card p-3 mb-4"><div class="row g-2">
<div class="col-md-3"><input name="q" value="<?=e($s)?>" class="form-control" placeholder="Event name"></div>
<div class="col-md-2"><select name="cat" class="form-select"><option value="">All categories</option><?php foreach ($cats as $x): ?><option value="<?=$x['id']?>" <?=$c==$x['id']?'selected':''?>><?=e($x['name'])?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input name="loc" value="<?=e($l)?>" class="form-control" placeholder="Location"></div>
<div class="col-md-2"><select name="sort" class="form-select"><option value="asc">Date: soonest</option><option value="desc" <?=$ord=='DESC'?'selected':''?>>Date: latest</option></select></div>
<div class="col-md-2 d-flex align-items-center"><div class="form-check"><input type="checkbox" name="upcoming" value="1" id="up" class="form-check-input" <?=!empty($_GET['upcoming'])?'checked':''?>><label for="up" class="form-check-label">Upcoming only</label></div></div>
<div class="col-md-1"><button class="btn btn-accent w-100">Go</button></div></div></form>
<div class="row g-4"><?php if (!$rows) echo '<div class="text-center text-muted py-5"><i class="bi bi-search fs-1"></i><p>No events match your filters.</p></div>'; foreach ($rows as $v) card($v); ?></div>
<div class="mt-4"><?=pager($total, $per, $page)?></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
