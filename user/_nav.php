<div class="container py-4"><ul class="nav nav-pills mb-4 gap-1">
<?php foreach (['dashboard'=>'Overview','my-events'=>'My Events','profile'=>'Profile & Password'] as $k => $t): ?>
<li class="nav-item"><a class="nav-link <?=($tab??'')==$k?'active bg-primary':''?>" href="/user/<?=$k?>.php"><?=$t?></a></li><?php endforeach; ?></ul>
