<div class="container py-4"><ul class="nav nav-pills mb-4 gap-1">
<?php foreach (['dashboard'=>'Overview','events'=>'Events','categories'=>'Categories','registrations'=>'Registrations','users'=>'Users','profile'=>'Profile'] as $k => $t): ?>
<li class="nav-item"><a class="nav-link <?=($tab??'')==$k?'active bg-primary':''?>" href="/admin/<?=$k?>.php"><?=$t?></a></li><?php endforeach; ?></ul>
