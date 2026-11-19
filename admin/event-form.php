<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Event Form'; $tab = 'events';
$id = (int)($_GET['id'] ?? 0); $err = '';
$ev = ['title'=>'','description'=>'','category_id'=>0,'location'=>'','venue'=>'','event_date'=>date('Y-m-d H:i', strtotime('+7 days')),'capacity'=>100,'image'=>'','published'=>1];
if ($id) $ev = q('SELECT * FROM events WHERE id=?', [$id])->fetch() ?: go('/admin/events.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  $ev = array_merge($ev, ['title'=>trim($_POST['title']), 'description'=>trim($_POST['description']), 'category_id'=>(int)$_POST['category_id'], 'location'=>trim($_POST['location']),
    'venue'=>trim($_POST['venue']), 'event_date'=>date('Y-m-d H:i:s', strtotime($_POST['event_date']) ?: time()), 'capacity'=>max(1, (int)$_POST['capacity']), 'published'=>isset($_POST['published']) ? 1 : 0]);
  if (!empty($_FILES['img']['name'])) { $f = $_FILES['img'];
    $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name'])] ?? null;
    if ($f['error'] || !$ext || $f['size'] > 2097152) $err = 'Image must be JPG, PNG or WEBP under 2 MB.';
    else { $n = bin2hex(random_bytes(8)).".$ext"; move_uploaded_file($f['tmp_name'], __DIR__."/../uploads/$n"); $ev['image'] = "/uploads/$n"; }
  } elseif (preg_match('#^https://#', $_POST['image_url'] ?? '')) $ev['image'] = trim($_POST['image_url']);
  if (!$err && (!$ev['title'] || !$ev['category_id'] || !$ev['location'])) $err = 'Title, category and location are required.';
  if (!$err) { $c = ['title','description','category_id','location','venue','event_date','capacity','image','published']; $v = array_map(fn($k) => $ev[$k], $c);
    if ($id) { q('UPDATE events SET '.implode('=?,', $c).'=? WHERE id=?', [...$v, $id]); }
    else q('INSERT INTO events('.implode(',', $c).') VALUES('.rtrim(str_repeat('?,', count($c)), ',').')', $v);
    flash('Event saved.'); go('/admin/events.php'); } }
$cats = q('SELECT * FROM categories ORDER BY name')->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="card p-4"><h4><?=$id?'Edit':'Create'?> event</h4><?php if ($err) echo '<div class="alert alert-danger">'.e($err).'</div>'; ?>
<form method="post" enctype="multipart/form-data" class="row g-3"><?=csrf()?>
<div class="col-md-8"><label class="form-label">Title</label><input name="title" class="form-control" value="<?=e($ev['title'])?>" required></div>
<div class="col-md-4"><label class="form-label">Category</label><select name="category_id" class="form-select" required><option value="">Select…</option><?php foreach ($cats as $c): ?><option value="<?=$c['id']?>" <?=$ev['category_id']==$c['id']?'selected':''?>><?=e($c['name'])?></option><?php endforeach; ?></select></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-control"><?=e($ev['description'])?></textarea></div>
<div class="col-md-4"><label class="form-label">City / location</label><input name="location" class="form-control" value="<?=e($ev['location'])?>" required></div>
<div class="col-md-4"><label class="form-label">Venue</label><input name="venue" class="form-control" value="<?=e($ev['venue'])?>"></div>
<div class="col-md-2"><label class="form-label">Date &amp; time</label><input type="datetime-local" name="event_date" class="form-control" value="<?=e(date('Y-m-d\TH:i', strtotime($ev['event_date'])))?>" required></div>
<div class="col-md-2"><label class="form-label">Capacity</label><input type="number" min="1" name="capacity" class="form-control" value="<?=(int)$ev['capacity']?>"></div>
<div class="col-md-6"><label class="form-label">Banner upload (JPG/PNG/WEBP, ≤2 MB)</label><input type="file" name="img" accept="image/*" class="form-control"></div>
<div class="col-md-6"><label class="form-label">…or image URL (https)</label><input name="image_url" class="form-control" value="<?=e($ev['image'])?>"></div>
<div class="col-12 form-check ms-2"><input type="checkbox" name="published" id="pb" class="form-check-input" <?=$ev['published']?'checked':''?>><label for="pb" class="form-check-label">Published</label></div>
<div class="col-12"><button class="btn btn-accent">Save event</button> <a href="/admin/events.php" class="btn btn-light">Cancel</a></div></form></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
