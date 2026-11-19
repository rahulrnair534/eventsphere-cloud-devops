<?php $adm = !empty($adminArea); ?><!doctype html><html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title ?? 'Home')?> · EventSphere</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link href="/assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand fw-bold" href="/"><i class="bi bi-calendar2-event"></i> EventSphere</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nv"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nv"><ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
<li class="nav-item"><a class="nav-link" href="/">Home</a></li><li class="nav-item"><a class="nav-link" href="/events.php">Events</a></li>
<?php if (!empty($_SESSION['uid'])): ?><li class="nav-item"><a class="nav-link" href="/user/dashboard.php">My Dashboard</a></li>
<?php elseif (!empty($_SESSION['aid'])): ?><li class="nav-item"><a class="nav-link" href="/admin/dashboard.php">Admin</a></li>
<?php else: ?><li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li><li class="nav-item"><a class="btn btn-accent btn-sm" href="/register.php">Sign up</a></li><?php endif; ?>
<?php if (!empty($_SESSION['uid']) || !empty($_SESSION['aid'])): ?><li><form method="post" action="/logout.php" class="m-0"><?=csrf()?><button class="btn btn-outline-light btn-sm">Logout</button></form></li><?php endif; ?>
</ul></div></div></nav>
<?php if (!empty($_SESSION['f'])): [$ft, $fm] = $_SESSION['f']; unset($_SESSION['f']); ?>
<div class="container mt-3"><div class="alert alert-<?=e($ft)?> alert-dismissible fade show"><?=e($fm)?><button class="btn-close" data-bs-dismiss="alert"></button></div></div><?php endif; ?>
