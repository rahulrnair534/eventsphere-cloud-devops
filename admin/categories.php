<?php require __DIR__.'/../includes/init.php'; need_admin(); $title = 'Categories'; $tab = 'categories';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { chk();
  if (isset($_POST['del'])) { q('SELECT 1'); try { q('DELETE FROM categories WHERE id=?', [(int)$_POST['del']]); flash('Category deleted.'); } catch (PDOException $x) { flash('Cannot delete: events use this category.', 'danger'); } }
  elseif (trim($_POST['name'] ?? '')) { try { q('INSERT INTO categories(name) VALUES(?)', [trim($_POST['name'])]); flash('Category added.'); } catch (PDOException $x) { flash('Category already exists.', 'danger'); } }
  go('/admin/categories.php'); }
$cats = q('SELECT * FROM categories ORDER BY name')->fetchAll();
include __DIR__.'/../includes/header.php'; include '_nav.php'; ?>
<div class="card p-3" style="max-width:520px"><form method="post" class="d-flex gap-2 mb-3"><?=csrf()?><input name="name" class="form-control" placeholder="New category" required><button class="btn btn-accent">Add</button></form>
<ul class="list-group"><?php foreach ($cats as $c): ?><li class="list-group-item d-flex justify-content-between"><?=e($c['name'])?>
<form method="post" data-confirm="Delete category?"><?=csrf()?><button name="del" value="<?=$c['id']?>" class="btn btn-sm btn-outline-danger">Delete</button></form></li><?php endforeach; ?></ul></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
