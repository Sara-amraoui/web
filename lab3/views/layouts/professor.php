<?php
declare(strict_types=1);
/** @var string $viewFile */
/** @var string $title */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body class="app-body">
<div class="app-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">◇</span>
            <div>
                <div class="brand-title">GPA Manager</div>
                <div class="brand-sub">Faculty</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link" href="<?= e(url('professor_grades')) ?>">Grade entry</a>
            <hr class="sidebar-rule">
            <a class="nav-link text-danger-emphasis" href="<?= e(url('logout')) ?>">Sign out</a>
        </nav>
        <div class="sidebar-user">
            <div class="small text-muted">Signed in</div>
            <div class="fw-semibold"><?= e(Auth::name() ?? '') ?></div>
        </div>
    </aside>
    <main class="main-content">
        <header class="top-bar">
            <h1 class="h4 mb-0"><?= e($title) ?></h1>
        </header>
        <div class="content-pad">
            <?php require __DIR__ . '/../partials/flash.php'; ?>
            <?php require $viewFile; ?>
        </div>
    </main>
</div>
<script>window.APP_BASE = <?= json_encode(base_path(), JSON_THROW_ON_ERROR) ?>;</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('js/professor.js')) ?>"></script>
</body>
</html>
