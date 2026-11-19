<?php
// Central config + helpers (config, database, auth, functions in one file for easy explanation)
ini_set('session.cookie_httponly', 1); ini_set('session.use_strict_mode', 1);
session_start();
define('DB_HOST', getenv('DB_HOST') ?: 'db'); define('DB_NAME', getenv('DB_NAME') ?: 'eventsphere');
define('DB_USER', getenv('DB_USER') ?: 'root'); define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8080'); define('APP_ENV', getenv('APP_ENV') ?: 'production');
ini_set('display_errors', APP_ENV === 'development' ? 1 : 0);
define('EV_SQL', "SELECT e.*, c.name cat, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id AND r.status='registered') taken
 FROM events e JOIN categories c ON c.id=e.category_id WHERE e.published=1");
function db() { static $p; if (!$p) $p = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASSWORD,
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]); return $p; }
function q($sql, $args = []) { $s = db()->prepare($sql); $s->execute($args); return $s; }
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function go($p) { header("Location: $p"); exit; }
function flash($m, $t = 'success') { $_SESSION['f'] = [$t, $m]; }
function csrf() { if (empty($_SESSION['t'])) $_SESSION['t'] = bin2hex(random_bytes(16)); return '<input type="hidden" name="t" value="'.$_SESSION['t'].'">'; }
function chk() { if (!hash_equals($_SESSION['t'] ?? '', $_POST['t'] ?? '')) { http_response_code(400); die('Invalid CSRF token'); } }
function need_user() { if (empty($_SESSION['uid'])) go('/login.php'); }
function need_admin() { if (empty($_SESSION['aid'])) go('/admin/login.php'); }
function pager($total, $per, $page) {
  $n = (int)ceil($total / $per); if ($n < 2) return ''; $h = '<nav><ul class="pagination justify-content-center">';
  for ($i = 1; $i <= $n; $i++) { $qs = http_build_query(array_merge($_GET, ['page'=>$i]));
    $h .= '<li class="page-item'.($i==$page?' active':'').'"><a class="page-link" href="?'.e($qs).'">'.$i.'</a></li>'; }
  return $h.'</ul></nav>'; }
function card($v) { $left = $v['capacity'] - $v['taken']; ?>
<div class="col-md-6 col-lg-4"><div class="card ev h-100">
<img src="<?=e($v['image'] ?: 'https://picsum.photos/seed/ev'.$v['id'].'/600/360')?>" class="card-img-top" loading="lazy" alt="">
<div class="card-body"><span class="badge badge-cat"><?=e($v['cat'])?></span>
<h5 class="mt-2"><a href="/event-details.php?id=<?=$v['id']?>" class="stretched-link text-decoration-none"><?=e($v['title'])?></a></h5>
<p class="small text-muted mb-1"><i class="bi bi-calendar-event"></i> <?=date('D, d M Y · g:i A', strtotime($v['event_date']))?></p>
<p class="small text-muted mb-1"><i class="bi bi-geo-alt"></i> <?=e($v['location'])?></p>
<p class="small mb-0 <?=$left>0?'text-success':'text-danger'?>"><?=$left>0?"$left seats left":'Fully booked'?></p></div></div></div>
<?php }
